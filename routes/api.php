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

Route::get('produk', [ProdukController::class, 'index']);

Route::middleware('auth:sanctum')->controller(UserController::class)->prefix('owner')->group(function () {
    Route::get('/', 'getUser');
});
Route::middleware('auth:sanctum')->controller(PesananController::class)->prefix('sales')->group(function () {
    Route::post('mesan/{user}', 'store');
    Route::post('update/{pesanan}', 'update');
    Route::post('dalate/{pesanan}', 'dalete');
    Route::get('pesanan/{user}', 'many');
});