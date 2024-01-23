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
        $stocks = Stock::all();

        $stockData = [];

        foreach ($stocks as $stock) {
            $cacheKey = "stock_price_{$stock->symbol}";
            $cachedData = Cache::get($cacheKey);

            if ($cachedData) {
                $currentPrice = $cachedData['Global Quote']['05. price'];
                $openPrice = $cachedData['Global Quote']['02. open'];
                $highPrice = $cachedData['Global Quote']['03. high'];
                $lowPrice = $cachedData['Global Quote']['04. low'];
                $volume = $cachedData['Global Quote']['06. volume'];
                $latestTradingDay = $cachedData['Global Quote']['07. latest trading day'];
                $previousClose = $cachedData['Global Quote']['08. previous close'];
                $changeAmount = $cachedData['Global Quote']['09. change'];

                // Retrieve the previous price from the database
                $previousPrice = StockPrice::where('stock_id', $stock->id)
                    ->orderBy('created_at', 'desc')
                    ->value('current_price');

                // Calculate percentage change
                $percentageChange = ($currentPrice - $previousPrice) / $previousPrice * 100;

                // Add data to the result array
                $stockData[$stock->symbol] = [
                    'current_price' => $currentPrice,
                    'percentage_change' => $percentageChange,
                    'open_price' => $openPrice,
                    'high_price' => $highPrice,
                    'low_price' => $lowPrice,
                    'volume' => $volume,
                    'latest_trading_day' => $latestTradingDay,
                    'previous_close' => $previousClose,
                    'change_amount' => $changeAmount,
                ];
            }
        }

        return response()->json($stockData);
    }
}
