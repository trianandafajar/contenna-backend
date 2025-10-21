<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Resources\UserController;

Route::middleware('auth')->prefix('resources')->name('resources.')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/users/login-as-user/{userId}', [UserController::class, 'loginAsUser'])->name('login.ask');
    Route::get('/users/user-verify/{userId}', [UserController::class, 'verifyEmail'])->name('user.verify');
    Route::resource('permissions', App\Http\Controllers\Resources\PermissionController::class);
    Route::resource('roles', App\Http\Controllers\Resources\RoleController::class);

    Route::prefix('user-deleted')->name('user-deleted.')->group(function () {
        Route::get('/', [App\Http\Controllers\Resources\UserController::class, 'UserDeletedIndex'])->name('index');
        Route::get('/show/{id}', [App\Http\Controllers\Resources\UserController::class, 'UserDeletedShow'])->name('show');
        Route::get('/{id}/recovery', [App\Http\Controllers\Resources\UserController::class, 'UserDeletedRecovery'])->name('recovery');
        Route::get('/{id}/login_as', [App\Http\Controllers\Resources\UserController::class, 'loginAsUser'])->name('loginAs');
        Route::delete('/deleted/{id}', [App\Http\Controllers\Resources\UserController::class, 'UserDeletedPermanent'])->name('destroy');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('general', [App\Http\Controllers\Resources\SettingController::class, 'general'])->name('general.index');
        Route::post('general', [App\Http\Controllers\Resources\SettingController::class, 'update_general'])->name('general.update');

        Route::get('register', [App\Http\Controllers\Resources\SettingController::class, 'register'])->name('register.index');
        Route::post('register', [App\Http\Controllers\Resources\SettingController::class, 'update_register'])->name('register.update');

        Route::get('smtp', [App\Http\Controllers\Resources\SettingController::class, 'smtp'])->name('smtp.index');
        Route::post('smtp/update', [App\Http\Controllers\Resources\SettingController::class, 'update_smtp'])->name('smtp.update');

        Route::resource('config', App\Http\Controllers\Resources\ConfigController::class);

    });
});
