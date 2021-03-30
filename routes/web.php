<?php

use App\Http\Controllers\ActorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\TvController;



Route::get('/', [MoviesController::class, 'index'])->name('movies.index');
Route::get('/movies/{id}', [MoviesController::class, 'show'])->name('movies.show');
Route::get('/movies/player/{hash}', [MoviesController::class, 'player'])->name('movies.player');

Route::get('/tv', [TvController::class, 'index'])->name('tv.index');
Route::get('/tv/{id}', [TvController::class, 'show'])->name('tv.show');

Route::get('/actors', [ActorsController::class, 'index'])->name('actors.index');
Route::get('/actors/page/{page?}', [ActorsController::class, 'index']);

Route::get('/actors/{id}', [ActorsController::class, 'show'])->name('actors.show');
