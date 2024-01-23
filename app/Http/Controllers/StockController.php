<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StockController extends Controller
{
    private $apiKey = '4R6YMFQ12FWS8OV7';
    private $stocks = ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'TSLA', 'NVDA', 'PYPL', 'NFLX', 'INTC', 'CSCO', 'IBM'];

    public function index()
    {
        $stocks = Stock::all();

        return response()->json($stocks);
    }

    public function fetchStockData()
    {
        foreach ($this->stocks as $stock) {
            $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$stock}&apikey={$this->apiKey}";
            $response[] = Http::get($url)->json();
        }

        return response()->json([
            'data' => $response,
            'message' => 'Stock data updated successfully'
        ]);
    }
}
