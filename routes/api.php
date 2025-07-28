<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PostsController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Handle Auth
    Route::post('register', [JWTAuthController::class, 'register']);
    Route::post('login', [JWTAuthController::class, 'login']);

    // Handle posts routes
    Route::middleware(JWTMiddleware::class)->prefix('posts')->group(function () {
        Route::get('/', [PostsController::class, 'index']); // Mengambil semua data posts
        Route::post('/', [PostsController::class, 'store']); // Menyimpan data post baru
        Route::get('{id}', [PostsController::class, 'show']); // Mengambil data post berdasarkan ID
        Route::put('{id}', [PostsController::class, 'update']); // Mengupdate data post berdasarkan ID
        Route::delete('{id}', [PostsController::class, 'destroy']); // Menghapus data post berdasarkan ID
    });

    // Handle comments routes
    Route::middleware(JWTMiddleware::class)->prefix('comments')->group(function () {
        Route::post('/', [CommentsController::class, 'store']); // Menyimpan data komentar baru
        Route::delete('{id}', [CommentsController::class, 'destroy']); // Menghapus komentar berdasarkan ID
    });

    // Handle likes routes
    Route::middleware(JWTMiddleware::class)->prefix('likes')->group(function () {
        Route::post('/', [LikesController::class, 'store']); // Menyimpan data like baru
        Route::delete('{id}', [LikesController::class, 'destroy']); // Menghapus like berdasarkan ID
    });

    // Handle messages routes
    Route::middleware(JWTMiddleware::class)->prefix('messages')->group(function () {
        Route::post('/', [MessagesController::class, 'store']); // Mengirim pesan
        Route::get('{id}', [MessagesController::class, 'show']); // Melihat detail pesan
        Route::get('/getMessages/{user_id}', [MessagesController::class, 'getMessages']); // Melihat pesan masuk berdasarkan user_id
        Route::delete('{id}', [MessagesController::class, 'destroy']); // Menghapus pesan
    });

});
