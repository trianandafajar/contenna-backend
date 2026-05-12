<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiContentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ManageBlogController;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\VersionLogController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Accounts\ProfileController;
use App\Http\Controllers\BlogsFilterController;

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
Route::get('/about', [DefaultController::class, 'about'])->name('about');
Route::get('/blogs', [BlogsFilterController::class, 'index'])->name('blogs');
Route::get('/blogs/{blog:slug}', [DefaultController::class, 'showBlog'])->name('blogs.show');
Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');

Route::get('/{slug}', [SinglePageController::class, 'show'])->name('single.show');

Route::get('/admin/login', function () {
    return redirect()->route('login');
});

Route::get('/admin/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::prefix("account")->name("account.")->group(function () {
        //My Financial
        Route::get('/overview', [ProfileController::class, 'overview'])->name('profile.overview');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/change-password', [ProfileController::class, 'password'])->name('profile.password');
        Route::patch('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix("feedback")->name("feedback.")->group(function () {
        route::get('/', [FeedbackController::class, 'index'])->name('index');
        route::get('/create', [FeedbackController::class, 'indexCreate'])->name('create');
        route::get('/show/{id}', [FeedbackController::class, 'show'])->name('show');
        route::post('/store', [FeedbackController::class, 'store'])->name('store');
    });

    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('version-logs', [VersionLogController::class, 'index_version'])->name('version.logs');
    Route::prefix("log-version")->name("log-version.")->group(function () {
        Route::get('/', [VersionLogController::class, 'index'])->name('index');
        Route::get('/create', [VersionLogController::class, 'create'])->name('create');
        Route::post('/store', [VersionLogController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [VersionLogController::class, 'edit'])->name('edit');
        Route::get('/show/{id}', [VersionLogController::class, 'show'])->name('show');
        Route::put('/update/{id}', [VersionLogController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [VersionLogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/edit/{slug}', [BlogController::class, 'edit'])->name('edit');
        Route::get('/show/{slug}', [BlogController::class, 'show'])->name('show');
        Route::put('/update/{slug}', [BlogController::class, 'update'])->name('update');
        Route::delete('/delete/{slug}', [BlogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('manage-blog')->name('manage-blog.')->group(function () {
        Route::get('/', [ManageBlogController::class, 'index'])->name('index');
        Route::get('/create', [ManageBlogController::class, 'create'])->name('create');
        Route::post('/store', [ManageBlogController::class, 'store'])->name('store');
        Route::get('/edit/{slug}', [ManageBlogController::class, 'edit'])->name('edit');
        Route::get('/show/{slug}', [ManageBlogController::class, 'show'])->name('show');
        Route::put('/update/{slug}', [ManageBlogController::class, 'update'])->name('update');
        Route::delete('/delete/{slug}', [ManageBlogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('ai')->name('ai.')->group(function () {
        Route::post('/generate-content', [AiContentController::class, 'generateContent'])->name('generate-content');
        Route::post('/generate-titles', [AiContentController::class, 'generateTitles'])->name('generate-titles');
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
