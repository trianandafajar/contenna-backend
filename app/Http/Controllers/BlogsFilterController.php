<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogsFilterController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query dasar dengan relasi
        $query = Blog::with('category', 'tags', 'user');

        // Cek apakah input form ada di category
        if ($request->has('q')) {
            $search = $request->q;
            
            // Cek apakah input form cocok dengan kategori
            $category = Category::where('slug', $search)->first();
            if ($category) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
            } else {
                // Cek apakah input form cocok dengan tag
                $tag = Tag::where('slug', $search)->first();
                if ($tag) {
                    $query->whereHas('tags', function ($q) use ($tag) {
                        $q->where('tags.id', $tag->id);
                    });
                } else {
                    // Jika bukan kategori atau tag, cari berdasarkan title
                    $query->where('title', 'LIKE', "%{$search}%");
                }
            }
        }

        // Ambil blog dengan paginasi
        $blogs = $query->latest()->paginate(12)->through(function ($blog) use ($user) {
            $blog->is_bookmarked = $user
                ? $user->bookmarks()->where('blog_id', $blog->id)->exists()
                : false;
            return $blog;
        });

        if ($request->ajax()) {
            return response()->json($blogs);
        }

        // Ambil semua kategori & tag
        $categories = Category::all();
        $tags = Tag::all();

        // Ambil daftar bookmark user (jika login)
        $bookmarkedBlogs = $user ? $user->bookmarks()->pluck('blogs.id')->toArray() : [];

        return view('frontend.pages.blogsFilter', compact('blogs', 'categories', 'tags', 'bookmarkedBlogs'));
    }
}
