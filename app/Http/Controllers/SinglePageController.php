<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinglePageController extends Controller
{
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

    return view('frontend.pages.single_page', compact('blog', 'categories', 'recommendedBlogs', 'splitContent','bookmarkedBlogs'));
}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
    
        // Ambil data blog dengan relasi category dan tags
        $query = Blog::with(['category', 'tags'])->latest();
    
        $user = auth()->user();
        $roles = $user->roles->pluck('name'); 
        
        if ($user) {
            $query = Blog::where('author_id', $user->id)->latest();
        }
        
        $query->each(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = \Illuminate\Support\Str::slug($blog->title, '-');
                $blog->save();
            }
        });
    
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
    
        $categories = Category::all();
        $blogs = $query->paginate($perPage);
    
        return view('pages.blog.index', compact('blogs', 'categories'));
    }
    
    
}

