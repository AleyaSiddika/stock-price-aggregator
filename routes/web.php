<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockPriceController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('stocks')->group(function () {
    Route::get('/', [StockController::class, 'index']);
    Route::get('/data', [StockController::class, 'fetchStockData']);

    Route::prefix('price')->group(function () {
        Route::get('/latest', [StockPriceController::class, 'getAllLatestStockPricesFromCache']);
        Route::get('/real-time', [StockPriceController::class, 'getRealTimeStockPricesWithPercentageChange']);
    });
});
