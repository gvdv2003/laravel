<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/contact', function() {
    return 'mij niet belluh';
})->name('contact');

//Route::get('/about-us', function() {
 //   $company = 'Hogeschool Rotterdam';
   // return view('about-us', [
     //   'company' => $company
    //]);
//})->name('about-us');

Route::get('products/{name}', function(string $name = null) {
    return view('products', [
        'name' => $name
    ]);
});

route::get('/about-us/{id}', [AboutUsController::class,'index',]);



require __DIR__.'/auth.php';
