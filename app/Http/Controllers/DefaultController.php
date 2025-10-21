<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('q'); // Ambil input pencarian

        // Query dasar dengan relasi dan sorting
        $query = Blog::with(['user', 'category', 'tags'])
            ->orderBy('created_at', 'desc');

        // Jika ada pencarian, filter berdasarkan title, category, atau tag
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%") // Cari di title
                    ->orWhereHas('category', function ($q) use ($search) { // Cari di category name
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tags', function ($q) use ($search) { // Cari di tags name
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Ambil daftar blog yang sudah di-bookmark oleh user
        $bookmarkedBlogs = $user ? $user->bookmarks()->pluck('blog_id')->toArray() : [];

        // Ambil hasil dengan pagination & tambahkan status bookmark
        $blogs = $query->paginate(12)
            ->through(function ($blog) use ($user) {
                $blog->is_bookmarked = $user
                    ? $user->bookmarks()->where('blog_id', $blog->id)->exists()
                    : false;
                return $blog;
            });

        // Jika request menggunakan AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json($blogs);
        }

        return view('frontend.pages.index', compact('blogs', 'bookmarkedBlogs'));
    }


    // Blog View
    public function showBlog(Request $request, Blog $blog)
    {
        if (!$blog) {
            abort(404, 'Blog tidak ditemukan.');
        }

        $modifiedContent = $this->processPreTags($blog->content);

        $relatedPosts = Blog::with(['user', 'category', 'tags'])
            ->where('id', '!=', $blog->id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('pages.blog.show', compact('modifiedContent', 'blog', 'relatedPosts'));
    }

    // Fungsi untuk memproses tag <pre>
    private function processPreTags($content)
    {
        if (!$content) {
            return '';
        }

        $pattern = '/<pre[^>]*id="([^"]*)"[^>]*>(.*?)<\/pre>/is';

        return preg_replace_callback($pattern, function ($matches) {
            $preId = $matches[1];
            $preContent = htmlspecialchars($matches[2]); // Hindari XSS dengan htmlspecialchars

            return '<div class="code-block">
                        <button class="z-[1] copy-button" title="Copy this Code">
                            <svg fill="#FFFFFF" width="20px" height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <title>ionicons-v5-e</title>
                                <path d="M456,480H136a24,24,0,0,1-24-24V128a16,16,0,0,1,16-16H456a24,24,0,0,1,24,24V456A24,24,0,0,1,456,480Z"/>
                                <path d="M112,80H400V56a24,24,0,0,0-24-24H60A28,28,0,0,0,32,60V376a24,24,0,0,0,24,24H80V112A32,32,0,0,1,112,80Z"/>
                            </svg>
                        </button>
                        <pre><code class="' . e($preId) . '" data-index="' . e($preId) . '">' . $preContent . '</code></pre>
                    </div>';
        }, $content);
    }

    public function about()
    {
        return view('frontend.pages.about');
    }
}
