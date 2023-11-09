<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
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

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/', [AuthController::class,'posts']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/user/message', [MessageController::class, 'message']);
});