<?php

namespace Tests\Feature\Console;

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchStockDataTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchStockDataCommand()
    {
        // Mock the HTTP response
        Http::fake([
            'https://www.alphavantage.co/*' => Http::response($this->getFakeApiResponse(), 200),
        ]);

        // Clear the cache before running the command
        Cache::flush();

        // Run the fetch-stock-data command
        $this->artisan('app:fetch-stock-data');

        // Assert: Check if the data is processed and saved to the database
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $this->assertDatabaseHas('stocks', ['symbol' => $stock->symbol]);
            $this->assertDatabaseHas('stock_prices', ['stock_id' => $stock->id]);
        }

        // Assert: Check if the cache is populated
        foreach ($stocks as $stock) {
            $cacheKey = "stock_price_{$stock->symbol}";
            $this->assertTrue(Cache::has($cacheKey));
        }
    }

    private function getFakeApiResponse()
    {
        // Provide a fake API response similar to the real one for testing
        return [
            'Global Quote' => [
                '01. symbol' => 'AAPL',
                '02. open' => '150.00',
                '03. high' => '155.00',
                '04. low' => '148.00',
                '05. price' => '152.50',
                '06. volume' => '1000000',
                '07. latest trading day' => '2022-01-01',
                '08. previous close' => '149.50',
                '09. change' => '3.00',
                '10. change percent' => '2.01%',
            ],
        ];
    }
}
