<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\Omzet;
use App\Models\Circle;
use App\Models\Business;
use App\Models\Ecosystem;
use Illuminate\Http\Request;
use App\Models\OmzetCategory;
use App\Models\BusinessMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $countMember = User::with('roles')->where('email', '!=', 'admin@gmail.com')->whereNull('deleted_at')->count();
        $currentMonth = date('m');
        $currentYear = date('Y');
    
        $memberRoute = $this->getRouteBasedOnPermission([
            'users-view' => 'resources.users.index',
            // 'member-fe' => 'business.member',
        ]);

        
        $userCount = User::all()->count();
        $blogCount = Blog::all()->count();
        $bookmarkedBlogs = $user->bookmarks()->take(3)->get();
        

        return view('pages.dashboard',compact('countMember','memberRoute', 'userCount', 'blogCount','bookmarkedBlogs'));
    }

    private function getRouteBasedOnPermission($permissionsRoutes) {
        foreach ($permissionsRoutes as $permission => $route) {
            if (auth()->user()->hasPermissionTo($permission)) {
                return route($route);
            }
        }
        return '#';
    }
}
