<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\SmartTvController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SlideController::class, 'index'])->name('home');
Route::get('/posts/smarttv/{smartTv}', [PostController::class, 'showBySmartTv'])->name('posts.showBySmartTv');

Route::middleware('auth')->group(function () {
    Route::get('/manage', [DashboardController::class, 'index'])->name('manage.dashboard');

    Route::get('/manage/post', [PostController::class, 'index'])->name('manage.post');
    Route::get('/manage/post/{id}', [PostController::class, 'show'])->name('manage.post.show');
    Route::post('/manage/post/store', [PostController::class, 'store'])->name('manage.post.store');
    Route::post('/manage/post/reorder', [PostController::class, 'reorder'])->name('manage.post.reorder');
    Route::put('/manage/post/{post}', [PostController::class, 'update'])->name('manage.post.update');
    Route::delete('/manage/post/{post}', [PostController::class, 'destroy'])->name('manage.post.destory');
    Route::post('/manage/post/restore',  [PostController::class, 'bulkRestore'])
        ->name('manage.post.restore');
    Route::post('/manage/post/delete', [PostController::class, 'bulkForceDelete'])
        ->name('manage.post.delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/manage/smarttvs')->name('manage.smarttvs.')->group(function () {
        Route::get('/', [SmartTvController::class, 'index'])->name('index');
        Route::post('/', [SmartTvController::class, 'store'])->name('store');
        Route::put('/{smarttv}', [SmartTvController::class, 'update'])->name('update');
        Route::delete('/{smarttv}', [SmartTvController::class, 'destroy'])->name('destroy');
        Route::get('/{smarttv}', [SmartTvController::class, 'show'])->name('show');
        Route::post('/restore', [SmartTvController::class, 'restore'])->name('restore');
        Route::post('/force', [SmartTvController::class, 'forceDelete'])->name('forceDelete');
    });
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('manage')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('manage.users');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('manage.users.show');
    Route::post('/users', [UserController::class, 'store'])->name('manage.users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('manage.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('manage.users.destroy');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('manage.users.restore');
    Route::post('/users/bulk-force-delete', [UserController::class, 'bulkForceDelete'])->name('manage.users.bulkForceDelete');
});

require __DIR__ . '/auth.php';
