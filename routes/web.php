<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Rute publice pentru vizualizarea postărilor
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Rute protejate de autentificare
Route::middleware('auth')->group(function () {
    // Rute pentru profilul utilizatorului
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rute de administrare (necesită controlere specifice Admin)
    // Acest grup este acum protejat de 'auth' ȘI 'admin' middleware
    Route::middleware('admin')->group(function () { // <-- Rutele admin sunt deja în grupul 'auth' de mai sus
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
            Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class);
            Route::patch('comments/{comment}/approve', [\App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        });
    }); // <-- ACEASTĂ PARANTEZĂ LIPSEA SAU ERA PLASATĂ GREȘIT

    // Rute pentru comentarii (creare și ștergere)
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Include rutele de autentificare generate de Breeze
require __DIR__.'/auth.php';
