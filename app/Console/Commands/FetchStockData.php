<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FetchStockData extends Command
{

    protected $signature = 'app:fetch-stock-data';

    protected $description = 'Fetch stock data from Alpha Vantage';

    private $stocks = ['AAPL', 'GOOGL', 'MSFT', 'AMZN',  'TSLA', 'NVDA', 'PYPL', 'NFLX', 'INTC', 'CSCO', 'IBM'];

    private $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('ALPHA_VANTAGE_API_KEY', '');
    }

    public function handle()
    {
        foreach ($this->stocks as $stock) {
            $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$stock}&apikey={$this->apiKey}";

            $cacheKey = "stock_price_{$stock}";
            $cachedData = Cache::get($cacheKey);

            // Check if data is in the cache
            if (!$cachedData) {
                // Make API request
                $response = $this->makeRequest($url);
                if ($response && isset($response['Global Quote'])) {
                    try {
                        // Process and save to database
                        $stockModel = Stock::updateOrCreate(['symbol' => $stock], ['name' => $response['Global Quote']['01. symbol']]);
                        StockPrice::create([
                            'stock_id' => $stockModel->id,
                            'open_price' => $response['Global Quote']['02. open'],
                            'high_price' => $response['Global Quote']['03. high'],
                            'low_price' => $response['Global Quote']['04. low'],
                            'current_price' => $response['Global Quote']['05. price'],
                            'volume' => $response['Global Quote']['06. volume'],
                            'latest_trading_day' => $response['Global Quote']['07. latest trading day'],
                            'previous_close' => $response['Global Quote']['08. previous close'],
                            'change_amount' => $response['Global Quote']['09. change'],
                            'change_percent' => $response['Global Quote']['10. change percent'],
                        ]);
                        // Cache the data for 1 minute
                        Cache::put($cacheKey, $response, now()->addMinutes(1));
                    } catch (\Exception $e) {
                        // Log the exception
                        Log::error("Error processing stock data for {$stock}: " . $e->getMessage());
                    }
                } else {
                    // Log or handle the case when 'Global Quote' key is not present
                    Log::error("Invalid API response for {$stock}: " . json_encode($response));
                }
            }
        }
    }


    private function makeRequest($url)
    {
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                return $response->json();
            } else {
                // Log the error or handle as needed
                Log::error('API request failed: ' . $response->status());
                return null;
            }
        } catch (\Exception $e) {
            // Handle connection issues
            Log::error('API request failed: ' . $e->getMessage());
            return null;
        }
    }
}
