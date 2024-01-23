<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->references('id')->on('stocks')->onDelete('cascade');
            $table->decimal('open_price', 10, 4)->nullable();
            $table->decimal('high_price', 10, 4)->nullable();
            $table->decimal('low_price', 10, 4)->nullable();
            $table->decimal('current_price', 10, 4)->nullable();
            $table->unsignedBigInteger('volume')->nullable();
            $table->date('latest_trading_day')->nullable();
            $table->decimal('previous_close', 10, 4)->nullable();
            $table->decimal('change_amount', 10, 4)->nullable();
            $table->decimal('change_percent', 10, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_prices');
    }
};
