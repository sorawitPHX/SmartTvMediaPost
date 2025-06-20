<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SlideController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SlideController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/manage', [DashboardController::class, 'index'])->name('manage.dashboard');

    Route::get('/manage/post', [PostController::class, 'index'])->name('manage.post');
    Route::get('/manage/post/{id}', [PostController::class, 'show'])->name('manage.post.show');
    Route::post('/manage/post/store', [PostController::class, 'store'])->name('manage.post.store');
    Route::post('/manage/post/reorder', [PostController::class, 'reorder'])->name('manage.post.reorder');
    Route::put('/manage/post/{post}', [PostController::class, 'update'])->name('manage.post.update');
    Route::delete('/manage/post/{post}', [PostController::class, 'destroy'])->name('manage.post.destory');
    Route::post('/manage/post/{id}/restore', [PostController::class, 'restore'])->name('manage.post.restore');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
