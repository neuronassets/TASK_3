<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoriaController;

// define method stampaAlbero for view /albero-categorie
Route::get('/albero-categorie', [CategoriaController::class, 'stampaAlbero']);
