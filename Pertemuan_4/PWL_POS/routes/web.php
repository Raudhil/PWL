<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',function () {
    return view('Welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori',[KategoriController::class,'index']);
Route::get('/user', [UserController::class, 'index']);
