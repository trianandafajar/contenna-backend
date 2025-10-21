<?php
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix("master")->name("master.")->group(function () {
    Route::resource('categories',App\Http\Controllers\Master\CategoriesController::class);
    Route::resource('tags',App\Http\Controllers\Master\TagController::class);
});
