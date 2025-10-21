<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\TagPageController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ManageBlogController;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\VersionLogController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\PublishedBlogController;
use App\Http\Controllers\Accounts\ProfileController;
use App\Http\Controllers\BlogsFilterController;
use App\Http\Controllers\Resources\PermissionController;
use App\Http\Controllers\TagsFilter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Home Menu
Route::get('/', [DefaultController::class, 'index'])->name('home');
Route::get('/page/about', [DefaultController::class, 'about'])->name('about');
Route::get('/blogs', [BlogsFilterController::class, 'index'])->name('blogs');

// Route untuk pencarian
// Route::get('/search', [SearchController::class, 'search'])->name('search');

// Route::get('/blog/filter', [TagsFilter::class, 'filterByTags'])->name('blog.filter');
// Blog Routes
// Route::get('/blogs/blogs', [DefaultController::class, 'index'])->name('blogs.index');
Route::get('/page/blogs/{blog:slug}', [DefaultController::class, 'showBlog'])->name('blogs.show');
Route::put('/page/blog/{id}', [BlogController::class, 'update'])->name('blog.update');

//Single Page 
Route::get('/{slug}', [SinglePageController::class, 'show'])->name('single.show');

// Filter By Tag
// Route::get('/blogs/tag/{slug}', [TagPageController::class, 'filterByTag'])->name('tag.show');

// Filter By Category
// Route::get('/blogs/category/{slug}', [CategoryPageController::class, 'filterByCategory'])->name('category.filter');

// Route::get('/blog/{blog:slug}', [DefaultController::class, 'showBlog'])->name('blog.show');

Route::get('/page/login', function () {
    return redirect()->route('login');
});

Route::get('/page/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::prefix('page/help')->name('help.')->group(function () {
//     Route::get('/show/{slug}', [HelpController::class, 'show'])->name('show');
// })->middleware(['guest', 'auth']);


Route::middleware('auth')->group(function () {

    Route::prefix("page/account")->name("account.")->group(function () {
        //My Financial
        Route::get('/overview', [ProfileController::class, 'overview'])->name('profile.overview');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/change-password', [ProfileController::class, 'password'])->name('profile.password');
        Route::patch('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix("page/feedback")->name("feedback.")->group(function () {
        route::get('/', [FeedbackController::class, 'index'])->name('index');
        route::get('/create', [FeedbackController::class, 'indexCreate'])->name('create');
        route::get('/show/{id}', [FeedbackController::class, 'show'])->name('show');
        route::post('/store', [FeedbackController::class, 'store'])->name('store');
    });

    Route::get('page/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('page/version-logs', [VersionLogController::class, 'index_version'])->name('version.logs');
    Route::prefix("page/log-version")->name("log-version.")->group(function () {
        Route::get('/', [VersionLogController::class, 'index'])->name('index');
        Route::get('/create', [VersionLogController::class, 'create'])->name('create');
        Route::post('/store', [VersionLogController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [VersionLogController::class, 'edit'])->name('edit');
        Route::get('/show/{id}', [VersionLogController::class, 'show'])->name('show');
        Route::put('/update/{id}', [VersionLogController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [VersionLogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('page/blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/edit/{slug}', [BlogController::class, 'edit'])->name('edit');
        Route::get('/show/{slug}', [BlogController::class, 'show'])->name('show');
        Route::put('/update/{slug}', [BlogController::class, 'update'])->name('update');
        Route::delete('/delete/{slug}', [BlogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('page/manage-blog')->name('manage-blog.')->group(function () {
        Route::get('/', [ManageBlogController::class, 'index'])->name('index');
        Route::get('/create', [ManageBlogController::class, 'create'])->name('create');
        Route::post('/store', [ManageBlogController::class, 'store'])->name('store');
        Route::get('/edit/{slug}', [ManageBlogController::class, 'edit'])->name('edit');
        Route::get('/show/{slug}', [ManageBlogController::class, 'show'])->name('show');
        Route::put('/update/{slug}', [ManageBlogController::class, 'update'])->name('update');
        Route::delete('/delete/{slug}', [ManageBlogController::class, 'destroy'])->name('destroy');
    });

    Route::get('/tags/search', function (Request $request) {
            $search = $request->input('q');
            $tags = App\Models\Tag::where('name', 'LIKE', "%$search%")->get();
            return response()->json($tags);
        })->name('tags.search');
});

Route::post('page/bookmark', [BookmarkController::class, 'toggleBookmark']);
Route::get('page/bookmark', [BookmarkController::class, 'showBookmark'])->name('bookmark.index');
Route::delete('/bookmark/delete', [BookmarkController::class, 'deleteBookmark'])->name('bookmark.delete');

require __DIR__ . '/auth.php';
require __DIR__ . '/resources.php';
require __DIR__ . '/master.php';
