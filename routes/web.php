<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\RequireAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('posts', PostController::class)
    ->except(['index', 'show'])
    ->middleware(RequireAdmin::class);
Route::resource('posts', PostController::class)->only(['index', 'show']);
