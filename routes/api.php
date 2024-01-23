<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockPriceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('stocks')->group(function () {
    Route::get('/', [StockController::class, 'index']);
    Route::get('/data', [StockController::class, 'fetchStockData']);

    Route::prefix('price')->group(function () {
        Route::get('/latest', [StockPriceController::class, 'getAllLatestStockPricesFromCache']);
        Route::get('/real-time', [StockPriceController::class, 'getRealTimeStockPricesWithPercentageChange']);
    });
});
