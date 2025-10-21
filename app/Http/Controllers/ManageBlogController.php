<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Blog;
use App\Models\User;
use App\Models\BlogTag;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ManageBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-blog-view', ['only' => ['index']]);
        $this->middleware('permission:manage-blog-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage-blog-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage-blog-delete', ['only' => ['destroy']]);
    }

public function index(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort');
    $category = $request->input('category');

    $perPage = 10;
    $query = Blog::query(); 

    // search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('tags', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('user', function ($q) use ($search) {
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

    // category
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

    return view('pages.manage-blog.index', compact('blogs', 'categories'));
}


    public function create()
    {
        $categories = Category::get();
        $authors = User::get();
        $tags = Tag::get();

        return view("pages.manage-blog.create", compact('categories', 'authors', 'tags'));
    }

    public function store(Request $request)
    {
        $isDraft = $request->has('save_draft');

        $request->validate([
            'title' => 'required',
            'author_id' => 'required',
            'content' => 'required',
            'tags' => 'nullable|array',
            'category_id' => $isDraft ? 'nullable' : 'required|exists:categories,id',
        ]);

        $status = $request->input('save_draft') ?? $request->input('save') ?? '2';

        $blog                     = new Blog();
        $blog->title              = $request->title;
        $blog->slug               = \Illuminate\Support\Str::slug($request->title, '-');
        $blog->content            = $request->content;
        $blog->category_id        = $request->category_id;
        $blog->special_role       = $request->special_role;
        $blog->status             = $status;


        if (Auth::user()->hasRole('super-admin')) {
            $blog->author_id      = $request->author_id;
        } else {
            $blog->author_id      = Auth::id();
        }

        if ($request->hasFile('thumbnail')) {
            // Simpan gambar ke storage
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $blog->thumbnail = $thumbnailPath;
    
            // Optimasi gambar
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(Storage::disk('public')->path($thumbnailPath));

            // simpan gambar ke storage
            $featuredImagePath = $request->file('thumbnail')->store('featured-image', 'public');
            $blog->featured_image = $featuredImagePath;
        }

        if ($request->has('save_draft')) {
            $blog->status = '2';
        } else {
            $blog->status = '1';
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
                    $newTag = Tag::firstOrCreate(['name' => $tag],['slug' => str::slug($tag)]);
                    $tagIds[] = $newTag->id;
                }
            }
            $blog->tags()->sync($tagIds);
        }
        

        $message = [
            "status" => "success",
            "message" => "Data created successfully"
        ];

        return redirect()->route('manage-blog.index')->with("message", $message);
    }

    public function edit($slug)
    {

        $blog = Blog::where('slug', $slug)->first();
        $categories = Category::all();
        $authors = User::all();
        $tags = Tag::get();

        return view("pages.manage-blog.edit", compact('blog', 'categories', 'authors', 'tags'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title'           => 'required',
            'content'         => 'required',
        ]);

        

        $blog                     = Blog::where('slug', $slug)->first();
        $blog->title              = $request->title;
        $blog->slug               = \Illuminate\Support\Str::slug($request->title, '-');
        $blog->content            = $request->content;
        $blog->status             = '1';
        $special_role = $request->has('special_role') ?$request->special_role: '1'; 
        $blog->special_role = $special_role;
        $tags = $request->tags;

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
                    $newTag = Tag::firstOrCreate(['name' => $tag],['slug' => str::slug($tag)]);
                    $tagIds[] = $newTag->id;
                }
            }
        }
        $blog->update();

        // Sync pivot table (hapus tag yang tidak dipilih)
        $blog->tags()->sync($tagIds);

        $message = [
            "status" => "success",
            "message" => "Data updated successfully"
        ];

        if ($request->has('update_and_continue_editing')) {
            return redirect()->back()->with("message", $message);
        } else {
            return redirect()->route('manage-blog.index')->with("message", $message);
        }
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
    
        $view = Auth::check() ? 'pages.manage-blog.show' : 'pages.blog.public';
    
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

        $message = [
            "status" => "success",
            "message" => "Data deleted successfully"
        ];

        return redirect()->route('manage-blog.index')->with("message", $message);
    }
}
