<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function toggleBookmark(Request $request)
    {
        $user = Auth::user();
        $blogId = $request->blog_id;

        // Cek apakah sudah ada di pivot table
        if ($user->bookmarks()->where('blog_id', $blogId)->exists()) {
            $user->bookmarks()->detach($blogId); // Hapus bookmark
            return response()->json(['status' => 'removed']);
        } else {
            $user->bookmarks()->attach($blogId); // Tambahkan bookmark
            return response()->json(['status' => 'added']);
        }
    }

    public function showBookmark()
    {
        $user = Auth::user();

        // Ambil semua blog yang telah di-bookmark oleh user
        $bookmarkedBlogs = $user->bookmarks()->paginate(3);
        return view('bookmark.index', compact('bookmarkedBlogs'));
    }

    public function deleteBookmark(Request $request)
    {
        $user = Auth::user();
        $blogId = $request->blog_id;

        // Hapus hubungan dari tabel pivot
        $user->bookmarks()->detach($blogId);

        $message = [
            'status' => 'success',
            'message' => 'success delete data'
        ];

        return redirect()->route('bookmark.index')->with('message',$message);
    }
}
