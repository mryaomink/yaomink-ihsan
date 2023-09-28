<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthFlutController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DeviceController;

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

Route::post('login', [AuthFlutController::class, 'login']);
Route::post('register', [AuthFlutController::class, 'register']);

Route::post('absen', [AbsensiController::class, 'absenMasuk']);
Route::post('absen-pulang', [AbsensiController::class, 'absenPulang']);


// Route::middleware('auth:api')->get('/user/devices', 'DeviceController@index');

Route::middleware('auth:api')->group(function () {
    // Rute untuk mengambil data guru yang terautentikasi
    Route::get('guru', [AuthFlutController::class, 'user']);
    Route::get('user/devices', [DeviceController::class, 'index']);
    Route::post('/absen-masuk', [AbsensiController::class, 'absenMasuk']);

    // Rute untuk logout guru
    Route::post('logout', [AuthFlutController::class, 'logout']);
});

// Route::middleware(['auth:api', 'single_device'])->group(function () {
//     // Rute untuk mengambil data guru yang terautentikasi
//     Route::get('guru', [AuthFlutController::class, 'getUser']);
//     Route::get('user/devices', [DeviceController::class, 'index']);

//     // Rute untuk logout guru
//     Route::post('logout', [AuthFlutController::class, 'logout']);
// });
