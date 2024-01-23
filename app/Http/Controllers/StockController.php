<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// This controller is purely for testing purposes
class StockController extends Controller
{
    private $stocks = ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'TSLA', 'NVDA', 'PYPL', 'NFLX', 'INTC', 'CSCO', 'IBM'];

    public function index()
    {
        $stocks = Stock::all();

        return response()->json($stocks);
    }

    public function fetchStockData()
    {
        $apiKey = env('ALPHA_VANTAGE_API_KEY');
        foreach ($this->stocks as $stock) {
            $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$stock}&apikey={$apiKey}";
            $response[] = Http::get($url)->json();
        }

        return response()->json([
            'data' => $response,
            'message' => 'Stock data updated successfully'
        ]);
    }
}
