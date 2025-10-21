<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:blog-view', ['only' => ['index']]);
        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort');
        $category = $request->input('category');

        $user = auth()->user();
        $perPage = 10;
        $query = Blog::where('author_id', $user->id);


        // search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('tags', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Sorting
        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc'); // Terbaru duluan
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc'); // Terlama duluan
        } else {
            $query->orderBy('created_at', 'desc'); // Default
        }

        // Filter berdasarkan kategori slug
        if ($category && $category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        $categories = Category::get();


        $blogs = $query->paginate($perPage);


        $blogs->each(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = \Illuminate\Support\Str::slug($blog->title, '-');
                $blog->save();
            }
        });

        return view('pages.blog.index', compact('blogs', 'categories'));
    }


    public function create()
    {
        return view("pages.blog.create", [
            'categories' => Category::all(),
            'authors' => User::all(),
            'tags' => Tag::all(),
            'roles' => Role::pluck('name', 'id')->toArray(),
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'tags' => 'nullable|array|max:8',
            'special_role' => 'nullable|in:1,2',
        ]);

        $isSuperAdmin = Auth::user()->roles->contains('name', 'super-admin');
        $slug = Str::slug($request->title, '-');
        $originalSlug = $slug;

     
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . now()->format('YmdHis');
        }

        $blog = new Blog([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'author_id' => $isSuperAdmin ? $request->author_id : Auth::id(),
            'status' => $request->has('save_draft') ? '2' : '1',
            'special_role' => $request->has('special_role') ? $request->special_role : '1',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Simpan gambar ke storage
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $blog->thumbnail = $thumbnailPath;

            // Optimasi gambar
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(Storage::disk('public')->path($thumbnailPath));

            $featuredImagePath = $request->file('thumbnail')->store('featured-image', 'public');
            $blog->featured_image = $featuredImagePath;
        }

        $blog->save();

        if ($request->has('tags')) {
            $tagIds = [];

            foreach ($request->tags as $tag) {
                // Cek apakah tag sudah ada berdasarkan nama
                if (is_numeric($tag)) {
                    // Jika yang dikirim adalah angka, berarti ini ID tag yang sudah ada
                    $tagIds[] = (int) $tag;
                } else {
                    // Jika yang dikirim adalah teks, berarti ini tag baru
                    $newTag = Tag::firstOrCreate(['name' => $tag], ['slug' => str::slug($tag)]);
                    $tagIds[] = $newTag->id;
                }
            }
            $blog->tags()->sync($tagIds);
        }

        return redirect()->route('blog.index')->with("message", [
            "status" => "success",
            "message" => "Data created successfully"
        ]);
    }


    public function edit($slug)
    {

        $blog = Blog::where('slug', $slug)->first();
        $categories = Category::all();
        $authors = User::all();
        $tags = Tag::get();
        return view("pages.blog.edit", compact('blog', 'categories', 'authors', 'tags'));
    }

    public function update(Request $request, $slug)
    {

        $request->validate([
            'title'   => 'required',
            'content' => 'required',
            'special_role' => 'nullable|in:1,2',
        ]);
        $blog                     = Blog::where('slug', $slug)->first();
        $blog->title              = $request->title;
        $blog->category_id        = $request->category_id;
        $blog->slug               = $request->slug;
        $blog->content            = $request->content;
        $blog->status             = '1';
        $tags = $request->tags;
        $special_role = $request->has('special_role') ? $request->special_role : '1';
        $blog->special_role = $special_role;

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            // Simpan thumbnail baru
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $blog->thumbnail = $thumbnailPath;

            // Optimasi gambar
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(Storage::disk('public')->path($thumbnailPath));

            $featuredImagePath = $request->file('thumbnail')->store('featured-image', 'public');
            $blog->featured_image = $featuredImagePath;
        }

        // Sync tags (hapus yang tidak dipilih, tambahkan yang baru)
        $tagIds = [];
        if (!empty($request->tags)) {
            foreach ($request->tags as $tag) {
                // Cek apakah tag sudah ada berdasarkan nama
                if (is_numeric($tag)) {
                    // Jika yang dikirim adalah angka, berarti ini ID tag yang sudah ada
                    $tagIds[] = (int) $tag;
                } else {
                    // Jika yang dikirim adalah teks, berarti ini tag baru
                    $newTag = Tag::firstOrCreate(['name' => $tag], ['slug' => str::slug($tag)]);
                    $tagIds[] = $newTag->id;
                }
            }
        }

        // Perbarui relasi many-to-many
        $blog->tags()->sync($tagIds);

        $blog->save();

        return redirect()->route('blog.index')->with("message", [
            "status" => "success",
            "message" => "Data updated successfully"
        ]);

        return redirect()->route('blog.index')->with('message', $message);
    }


    public function show($slug)
    {
        $user = Auth::user();
        $blog = Blog::with(['category', 'tags', 'user'])->where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        $recommendedBlogs = Blog::where('id', '!=', $blog->id)->latest()->limit(5)->get();
    
        $content = $blog->content;
        $bookmarkedBlogs = $user ? $user->bookmarks()->pluck('blogs.id')->toArray() : [];
    
        // Pisahkan berdasarkan tag <pre> menggunakan regex
        $splitContent = preg_split('/(<pre.*?>.*?<\/pre>)/s', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    
        $view = Auth::check() ? 'pages.blog.show' : 'pages.blog.public';
    
        return view($view, compact('blog', 'categories', 'recommendedBlogs', 'splitContent', 'bookmarkedBlogs'));
    }
    

    public function destroy($slug)
    {

        $blog = Blog::where('slug', $slug)->firstOrFail();

        if ($blog->thumbnail && $blog->featured_image) {
            Storage::disk('public')->delete($blog->thumbnail);
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->tags()->detach();

        $blog->delete();

        return redirect()->route('blog.index')->with("message", [
            "status" => "success",
            "message" => "Data deleted successfully"
        ]);
    }
}
