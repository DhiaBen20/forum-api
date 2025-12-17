<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users/current', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('users', [UserController::class, 'store']);

Route::post('/tokens/create', [TokenController::class, 'store'])->name('tokens.create');
Route::delete('/tokens/current', [TokenController::class, 'destroy'])->middleware('auth:sanctum')->name('tokens.destroy');

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

// Notification Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::patch('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::patch('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
});
