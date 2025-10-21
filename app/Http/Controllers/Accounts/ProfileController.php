<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Blog;
use App\Models\Business;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\BusinessOwner;
use App\Models\BusinessMember;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{

    public function overview(Request $request)
    {
        $countBlog = Blog::where('author_id', Auth::user()->id)->count();
        return view('pages.accounts.profile.overview', [
            'user' => $request->user(),
            'countBlog' => $countBlog
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $countBlog = Blog::where('author_id', Auth::user()->id)->count();
        return view('pages.accounts.profile.edit', [
            'user' => $request->user(),
            'countBlog' => $countBlog
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email',
        ]);

        $user = auth()->user();

        $user->name = $request->name;

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        // dd($request);
        if ($request->file('avatar')) {
            $request->validate([
                'avatar' => 'required|file|image|max:2240',
            ]);

            $image = $request->file('avatar');

            $imagename = $image->getClientOriginalName();
            $a = explode(".", $imagename);
            $fileExt = strtolower(end($a));
            $namaFile = substr(md5(date("YmdHis")), 0, 10) . "." . $fileExt;
            $destination_path = public_path() . '/media/avatars/';

            $request->file('avatar')->move($destination_path, $namaFile);

            $request->user()->avatar = $namaFile;
        }

        $request->user()->save();

        $message = [
            "status" => "success",
            "message" => "Profile updated success"
        ];

        return Redirect::route('account.profile.edit')->with('message', $message);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function password(Request $request)
    {

        $countBlog = Blog::where('author_id', Auth::user()->id)->count();
        return view('pages.accounts.profile.password', [
            'user' => $request->user(),
            'countBlog' => $countBlog
        ]);
    }

    public function changePassword(Request $request)
    {

        $credentials = $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('current password tidak sesuai');
                    }
                },
            ],
            'password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required|min:8',
        ]);

        $request->user()->password = Hash::make($credentials["password"]);
        $request->user()->save();

        $message = [
            "status" => "success",
            "message" => "change password successfully"
        ];

        return Redirect::route('account.profile.password')->with('message', $message);
    }
}