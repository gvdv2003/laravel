<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::patch('/games/admin/{id}/toggleVisibility', [GameController::class, 'toggleVisibility'])->name('admin.toggleVisibility');

Route::get('/', [GameController::class, 'index'])->name('games.index');


Route::get('/games/admin', [GameController::class, 'adminIndex'])->name('games.admin');
// Resource route voor games, dit omvat alle noodzakelijke routes
Route::resource('/games', GameController::class)->except(['index', 'show']); // Uitsluiten van index en show

Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');

    Route::get('/games/{id}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/games/{id}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{id}', [GameController::class, 'destroy'])->name('games.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/games/{game}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

Route::get('products/{name}', function (string $name = null) {
    return view('products', [
        'name' => $name
    ]);
});

Route::get('/about-us/{id}', [AboutUsController::class, 'index']);

Route::resource('categories', CategoryController::class);

require __DIR__.'/auth.php';
