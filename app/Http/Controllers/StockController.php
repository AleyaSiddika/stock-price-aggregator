<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class StockController extends Controller
{
    private $apiKey = '4R6YMFQ12FWS8OV7';
    private $stocks = ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'FB', 'TSLA', 'NVDA', 'PYPL', 'NFLX', 'INTC', 'CSCO', 'IBM'];

    public function fetchStockData()
    {
        foreach ($this->stocks as $stock) {
            $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$stock}&apikey={$this->apiKey}";
            $response[] = $this->makeRequest($url);

            // Process and save $response as needed
            // Example: Save to a database
        }

        return response()->json([
            'data' => $response,
            'message' => 'Stock data updated successfully'
        ]);
    }

    private function makeRequest($url)
    {
        $client = new Client();
        $response = $client->get($url);
        return json_decode($response->getBody(), true);
    }
}

