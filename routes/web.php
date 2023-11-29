<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomepageController::class, 'index']);

// Posts
Route::middleware(['auth'])->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Likes
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
    
    // Comments
    Route::post('/posts/{post}/comments', [PostController::class, 'addComment'])->name('posts.comments.add');

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

// Public profiles
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    // Follows
    Route::post('/profile/{user}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::post('/profile/{user}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');
});


    


// Auth Routes
require __DIR__.'/auth.php';
