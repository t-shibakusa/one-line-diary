<?php

use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('diaries.index')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/diaries', [DiaryController::class, 'index'])->name('diaries.index');
    Route::get('/diaries/create', [DiaryController::class, 'create'])->name('diaries.create');
    Route::post('/diaries', [DiaryController::class, 'store'])->name('diaries.store');
    Route::get('/diaries/{diary}', [DiaryController::class, 'show'])->name('diaries.show');
    Route::get('/diaries/{diary}/edit', [DiaryController::class, 'edit'])->name('diaries.edit');
    Route::put('/diaries/{diary}', [DiaryController::class, 'update'])->name('diaries.update');
    Route::delete('/diaries/{diary}', [DiaryController::class, 'destroy'])->name('diaries.destroy');
    Route::get('/diaries/{diary}/image', [DiaryImageController::class, 'show'])->name('diaries.image');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
