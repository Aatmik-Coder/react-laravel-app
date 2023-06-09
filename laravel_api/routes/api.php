<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('products' ,[ProductController::class,'index']);
// Route::post('products' ,[ProductController::class,'store']);
Route::get('logout',[ProductController::class, 'logout']);
Route::apiResource('products', ProductController::class);

Route::post('login',[ProductController::class, 'login']);
Route::post('products/edit/image/{id}',[ProductController::class,'delete_image']);
Route::get('search/{key}',[ProductController::class,'search']);