<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UniverseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Stream audio with Range-request support (php artisan serve lacks this for static files)
Route::get('/stream/{path}', function (string $path) {
    $fullPath = storage_path('app/public/'.$path);

    if (! file_exists($fullPath) || ! Str::startsWith(realpath($fullPath), realpath(storage_path('app/public')))) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('stream');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/characters/{slug}', [CharacterController::class, 'show'])->name('characters.show');
Route::get('/novel', [NovelController::class, 'index'])->name('novel.index');
Route::get('/universe', [UniverseController::class, 'index'])->name('universe.index');
Route::get('/universe/{slug}', [UniverseController::class, 'show'])->name('universe.show');
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');

// Redirect old dashboard to admin
Route::middleware(['auth', 'verified'])->get('/dashboard', fn () => redirect('/admin'))->name('dashboard');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('characters', Admin\CharacterController::class)
        ->except(['show']);
    Route::post('characters/{character}/generate-profile', [Admin\CharacterController::class, 'generateProfile'])
        ->name('characters.generate-profile');
    Route::post('characters/{character}/generate-action-image', [Admin\CharacterController::class, 'generateActionImage'])
        ->name('characters.generate-action-image');

    Route::post('diary-entries/reorder', [Admin\DiaryEntryController::class, 'reorder'])->name('diary-entries.reorder');
    Route::resource('diary-entries', Admin\DiaryEntryController::class)
        ->except(['show']);

    Route::get('novel', [Admin\NovelController::class, 'edit'])->name('novel.edit');
    Route::put('novel', [Admin\NovelController::class, 'update'])->name('novel.update');

    Route::resource('universe', Admin\UniverseEntryController::class)
        ->except(['show']);

    Route::post('music-tracks/reorder', [Admin\MusicTrackController::class, 'reorder'])->name('music-tracks.reorder');
    Route::resource('music-tracks', Admin\MusicTrackController::class)
        ->except(['show']);

    Route::resource('products', Admin\ProductController::class)
        ->except(['show']);
});

require __DIR__.'/settings.php';
