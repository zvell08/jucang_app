<?php

use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//auth
Route::post('adduser', [UserController::class, 'storeUser']);
Route::post('login', [UserController::class, 'login']);
Route::get('sales', [UserController::class, 'getUser']);
// done
//sales
Route::post('mesan/{user}', [PesananController::class, 'store']);
Route::get('pesanan/{user}', [PesananController::class, 'many']);
Route::post('update/{pesanan}', [PesananController::class, 'update']);

Route::get('produk', [ProdukController::class, 'index']);