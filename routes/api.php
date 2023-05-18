<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Product APIs
Route::get('/product', [ProductController::class,'ListProduct']);
Route::post('/product', [ProductController::class,'UpdateProduct']);


// Order APIs
Route::get('/order', [OrderController::class,'ListOrder']);
Route::post('/order', [OrderController::class,'PlaceOrder']);
Route::put('/order/{id}', [OrderController::class,'UpdateOrder'],['id' => 0]);
Route::delete('/order/{id}', [OrderController::class,'DeleteOrder'],['id' => 0]);