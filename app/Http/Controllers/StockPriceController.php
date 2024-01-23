<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StockPriceController extends Controller
{
    public function getAllLatestStockPricesFromCache()
    {
        $allSymbols = Stock::pluck('symbol')->toArray();

        $allData = [];

        foreach ($allSymbols as $symbol) {
            $cacheKey = "stock_price_{$symbol}";
            $cachedData = Cache::get($cacheKey);

            if ($cachedData) {
                $allData[$symbol] = $cachedData;
            }
        }

        return response()->json($allData);
    }

    public function getRealTimeStockPricesWithPercentageChange()
    {
        // Assuming you have the symbols stored in the database
        $symbols = Stock::pluck('symbol')->toArray();

        $stockData = [];

        foreach ($symbols as $symbol) {
            $cacheKey = "stock_price_{$symbol}";
            $cachedData = Cache::get($cacheKey);

            if ($cachedData) {
                $currentPrice = $cachedData['Global Quote']['05. price'];

                // Retrieve the previous price from the database
                $previousPrice = StockPrice::where('symbol', $symbol)
                    ->orderBy('created_at', 'desc')
                    ->value('current_price');

                // Calculate percentage change
                $percentageChange = ($currentPrice - $previousPrice) / $previousPrice * 100;

                // Add data to the result array
                $stockData[$symbol] = [
                    'current_price' => $currentPrice,
                    'percentage_change' => $percentageChange,
                ];
            }
        }

        return response()->json($stockData);
    }
}
