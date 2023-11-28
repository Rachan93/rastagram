<?php

use App\Http\Controllers\HomepageController;
//use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomepageController::class, 'index']);

// Feed
//Route::middleware(['auth', 'verified'])->get('/feed', [FeedController::class, 'feed'])->name('feed');

// Post
Route::middleware(['auth'])->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});


// Dashboard
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes
require __DIR__.'/auth.php';
