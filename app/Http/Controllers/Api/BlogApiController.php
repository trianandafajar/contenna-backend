<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BlogCollection;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogListResource;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class BlogApiController extends Controller
{
    /**
     * Display a listing of blogs (PUBLIC - No Auth Required)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $category = $request->get('category');
        $tag = $request->get('tag');
        $status = $request->get('status', '1');
        $sort = $request->get('sort', 'newest');

        $query = Blog::with(['category', 'user', 'tags'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('content', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                        ->orWhereHas('tags', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                        ->orWhereHas('user', fn($q) => $q->where('name', 'LIKE', "%{$search}%"));
                });
            })
            ->when(
                $category,
                fn($q) =>
                $q->whereHas('category', fn($cat) => $cat->where('slug', $category))
            )
            ->when(
                $tag,
                fn($q) =>
                $q->whereHas('tags', fn($t) => $t->where('slug', $tag))
            )
            ->when($sort, function ($q, $sort) {
                return match ($sort) {
                    'oldest' => $q->orderBy('created_at', 'asc'),
                    'title' => $q->orderBy('title', 'asc'),
                    'top' => $q->orderByDesc('views'),
                    'featured' => $q->where('special_role', 2)->latest(),
                    default => $q->latest(),
                };
            });

        $blogs = $query->paginate($perPage);
        return response()->json([
            'data' => BlogListResource::collection($blogs->items()),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
                'from' => $blogs->firstItem(),
                'to' => $blogs->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created blog (PROTECTED - Auth Required)
     */
    public function store(StoreBlogRequest $request)
    {
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title, '-');
        $blog->content = $request->content;
        $blog->category_id = $request->category_id;
        $blog->special_role = $request->special_role ?? '1';
        $blog->status = $request->status ?? '1';
        $blog->author_id = Auth::id();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $blog->thumbnail = $thumbnailPath;

            // Optimize image
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(storage_path('app/public/' . $thumbnailPath));

            // Also save as featured image
            $featuredImagePath = $request->file('thumbnail')->store('featured-image', 'public');
            $blog->featured_image = $featuredImagePath;
        }

        $blog->save();

        // Handle tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tag) {
                if (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                } else {
                    $newTag = Tag::firstOrCreate(
                        ['name' => $tag],
                        ['slug' => Str::slug($tag)]
                    );
                    $tagIds[] = $newTag->id;
                }
            }
            $blog->tags()->sync($tagIds);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully',
            'data' => new BlogResource($blog->load(['category', 'user', 'tags']))
        ], 201);
    }

    /**
     * Display the specified blog (PUBLIC - No Auth Required)
     */
    public function show(Blog $blog)
    {
        if ($blog->status !== '1') {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found.'
            ], 404);
        }

        $blog->increment('views');
        $blog->load(['category', 'user', 'tags']);

        return response()->json([
            'status' => 'success',
            'data' => new BlogResource($blog)
        ]);
    }


    /**
     * Update the specified blog (PROTECTED - Auth Required)
     */
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);

        // Check if user can update this blog
        if ($blog->author_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this blog'
            ], 403);
        }

        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title, '-');
        $blog->content = $request->content;
        $blog->category_id = $request->category_id;
        $blog->special_role = $request->special_role ?? '1';
        $blog->status = $request->status ?? '1';

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old images
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            // Save new images
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $blog->thumbnail = $thumbnailPath;

            // Optimize image
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(storage_path('app/public/' . $thumbnailPath));

            $featuredImagePath = $request->file('thumbnail')->store('featured-image', 'public');
            $blog->featured_image = $featuredImagePath;
        }

        $blog->save();

        // Handle tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tag) {
                if (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                } else {
                    $newTag = Tag::firstOrCreate(
                        ['name' => $tag],
                        ['slug' => Str::slug($tag)]
                    );
                    $tagIds[] = $newTag->id;
                }
            }
            $blog->tags()->sync($tagIds);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully',
            'data' => new BlogResource($blog->load(['category', 'user', 'tags']))
        ]);
    }

    /**
     * Remove the specified blog (PROTECTED - Auth Required)
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        // Check if user can delete this blog
        if ($blog->author_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to delete this blog'
            ], 403);
        }

        // Delete associated images
        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        // Detach tags
        $blog->tags()->detach();

        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted successfully'
        ]);
    }

    /**
     * Search blogs with advanced filters (PUBLIC - No Auth Required)
     */
    public function search(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('q');
        $category = $request->get('category');
        $tags = $request->get('tags', []);
        $author = $request->get('author');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $status = $request->get('status', '1');
        $sort = $request->get('sort', 'newest');

        $query = Blog::with(['category', 'user', 'tags'])
            ->when($status, fn($q) => $q->where('status', $status))

            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('content', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                        ->orWhereHas('tags', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                        ->orWhereHas('user', fn($q) => $q->where('name', 'LIKE', "%{$search}%"));
                });
            })

            ->when(
                $category,
                fn($q) =>
                $q->whereHas('category', fn($cat) => $cat->where('slug', $category))
            )

            ->when(
                !empty($tags),
                fn($q) =>
                $q->whereHas('tags', fn($t) => $t->whereIn('slug', $tags))
            )

            ->when(
                $author,
                fn($q) =>
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('username', $author)
                        ->orWhere('name', 'LIKE', "%{$author}%")
                )
            )

            ->when(
                $dateFrom,
                fn($q) =>
                $q->whereDate('created_at', '>=', $dateFrom)
            )
            ->when(
                $dateTo,
                fn($q) =>
                $q->whereDate('created_at', '<=', $dateTo)
            )

            ->when($sort, function ($q, $sort) {
                return match ($sort) {
                    'oldest' => $q->orderBy('created_at', 'asc'),
                    'title' => $q->orderBy('title', 'asc'),
                    'top' => $q->orderByDesc('views'),
                    'featured' => $q->where('special_role', 2)->latest(),
                    default => $q->latest(),
                };
            });

        $blogs = $query->paginate($perPage);

        return response()->json([
            'data' => BlogListResource::collection($blogs->items()),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
                'from' => $blogs->firstItem(),
                'to' => $blogs->lastItem(),
            ],
        ]);
    }


    /**
     * Toggle bookmark for a blog (PROTECTED - Auth Required)
     */
    public function toggleBookmark($id)
    {
        $blog = Blog::findOrFail($id);
        $user = Auth::user();

        if ($user->bookmarks()->where('blogs.id', $id)->exists()) {
            $user->bookmarks()->detach($id);
            $status = 'removed';
        } else {
            $user->bookmarks()->attach($id);
            $status = 'added';
        }

        return response()->json([
            'status' => 'success',
            'message' => "Bookmark {$status} successfully",
            'bookmark_status' => $status
        ]);
    }

    /**
     * Get user's bookmarked blogs (PROTECTED - Auth Required)
     */
    public function bookmarks(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $user = Auth::user();

        $bookmarkedBlogs = $user->bookmarks()
            ->with(['category', 'user', 'tags'])
            ->paginate($perPage);

        return new BlogCollection($bookmarkedBlogs);
    }
}
