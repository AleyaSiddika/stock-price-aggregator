<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stock_prices', function (Blueprint $table) {
            $table->string('change_percent', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('stock_prices', function (Blueprint $table) {
            $table->decimal('change_percent', 10, 4)->nullable()->change();
        });
    }
};
