<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShortUrlController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::prefix('shorturl')->group(function () {
    Route::get('/', [ShortUrlController::class, 'generateShortUrl']);
});

Route::get('/{shortCode}', [ShortUrlController::class, 'redirectToOriginalUrl']);