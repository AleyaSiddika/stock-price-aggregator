<?php

namespace Tests\Feature;

use App\Models\Stock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_real_time_stock_prices_with_percentage_change_caching()
    {
        // Clear the cache before running the test
        Cache::flush();

        // Act: Hit the controller endpoint
        $response = $this->get('/stocks/price/real-time');

        // Assert: Check HTTP response status
        $response->assertStatus(200);

        // Assert: Check caching behavior
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $cacheKey = "stock_price_{$stock->symbol}";

            // Assert: Check if the cache exists
            $this->assertTrue(Cache::has($cacheKey));

            $cachedData = Cache::get($cacheKey);
            $this->assertArrayHasKey('Global Quote', $cachedData);
            $this->assertArrayHasKey('05. price', $cachedData['Global Quote']);

            // Assert: Check additional fields in the cached data
            $response->assertJson([
                'symbol' => $stock->symbol,
                'current_price' => $cachedData['Global Quote']['05. price'],
                'percentage_change' => $cachedData['percentage_change'],
                'open_price' => $cachedData['Global Quote']['02. open'],
                'high_price' => $cachedData['Global Quote']['03. high'],
                'low_price' => $cachedData['Global Quote']['04. low'],
                'volume' => $cachedData['Global Quote']['06. volume'],
                'latest_trading_day' => $cachedData['Global Quote']['07. latest trading day'],
                'previous_close' => $cachedData['Global Quote']['08. previous close'],
                'change_amount' => $cachedData['Global Quote']['09. change'],
            ]);
        }

        // Assert: Check if the response structure is as expected
        $response->assertJsonStructure([
            '*' => [
                'symbol',
                'current_price',
                'percentage_change',
                'open_price',
                'high_price',
                'low_price',
                'volume',
                'latest_trading_day',
                'previous_close',
                'change_amount',
            ],
        ]);

        // Assert: Check if the cached data matches the response data
        $response->assertJson(function ($json) use ($stocks) {
            foreach ($stocks as $stock) {
                $cacheKey = "stock_price_{$stock->symbol}";
                $cachedData = Cache::get($cacheKey);

                $json->where('symbol', $stock->symbol)
                    ->where('current_price', $cachedData['Global Quote']['05. price'])
                    ->where('percentage_change', $cachedData['percentage_change'])
                    ->where('open_price', $cachedData['Global Quote']['02. open'])
                    ->where('high_price', $cachedData['Global Quote']['03. high'])
                    ->where('low_price', $cachedData['Global Quote']['04. low'])
                    ->where('volume', $cachedData['Global Quote']['06. volume'])
                    ->where('latest_trading_day', $cachedData['Global Quote']['07. latest trading day'])
                    ->where('previous_close', $cachedData['Global Quote']['08. previous close'])
                    ->where('change_amount', $cachedData['Global Quote']['09. change']);
            }

            return true;
        });
    }
}
