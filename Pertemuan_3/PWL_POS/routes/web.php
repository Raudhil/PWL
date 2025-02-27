<?php

use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;

Route::get('/',function () {
    return view('Welcome');
});

Route::get('/level', [LevelController::class, 'index']);
