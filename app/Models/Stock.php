<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['symbol', 'name'];

    public function stockPrices()
    {
        return $this->hasMany(StockPrice::class);
    }
}
