<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/home', [HomeController::class, 'index']);

Route::get('/foodbeverage', [ProductController::class, 'foodBeverage']);

Route::get('/beautyhealth', [ProductController::class, 'beautyHealth']);

Route::get('/homecare', [ProductController::class, 'homeCare']);

Route::get('/babykid', [ProductController::class, 'babyKid']);

Route::get('/user/{id}/name/{name}', [UserController::class,'show']);

Route::get('/transaksi', [TransaksiController::class, 'halamanTransaksi']);
