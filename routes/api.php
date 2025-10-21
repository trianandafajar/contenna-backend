<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\LogoApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogApiController::class, 'index']); // List blogs
    Route::get('/search', [BlogApiController::class, 'search']); // Search blogs
    Route::get('/{id}', [BlogApiController::class, 'show']); // Get single blog
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Blog management (authenticated)
    Route::prefix('blogs')->group(function () {
        Route::post('/', [BlogApiController::class, 'store']); // Create blog
        Route::put('/{id}', [BlogApiController::class, 'update']); // Update blog
        Route::delete('/{id}', [BlogApiController::class, 'destroy']); // Delete blog
        Route::post('/{id}/bookmark', [BlogApiController::class, 'toggleBookmark']); // Toggle bookmark
    });

    // User bookmarks
    Route::get('/bookmarks', [BlogApiController::class, 'bookmarks']); // Get user bookmarks

    // Logo management (admin only)
    Route::prefix('logo')->group(function () {
        Route::get('/', [LogoApiController::class, 'index']); // Get logo
        Route::post('/', [LogoApiController::class, 'store']); // Upload logo
        Route::put('/', [LogoApiController::class, 'update']); // Update logo
        Route::delete('/', [LogoApiController::class, 'destroy']); // Delete logo
    });
});
