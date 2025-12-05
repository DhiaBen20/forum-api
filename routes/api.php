<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;

Route::get('user', function (#[CurrentUser()] User $user) {
    return $user;
})->middleware('auth:sanctum');

// Post Routes
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::patch('posts/{post:slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// Comment and Reply Routes
Route::get('/comments', [CommentController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/comments', [CommentController::class, 'store']);
    Route::patch('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Likes Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::Post('likes/{type}/{likeable}', [LikeController::class, 'store'])->whereIn('type', ['comments', 'posts']);
    Route::delete('likes/{type}/{likeable}', [LikeController::class, 'destroy'])->whereIn('type', ['comments', 'posts']);
});
