<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/movies');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('movies', MovieController::class);
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/add', [WatchlistController::class, 'add'])->name('watchlist.add');
    Route::delete('/watchlist/remove', [WatchlistController::class, 'remove'])->name('watchlist.remove');
});

Route::get('/api/movies', [MovieController::class, 'apiIndex']);
Route::get('/api/movies/{id}', [MovieController::class, 'apiShow']);

require __DIR__.'/auth.php';