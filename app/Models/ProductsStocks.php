<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsStocks extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'stock_id', 'price', 'quantity'];

    public function product()
{
    return $this->belongsTo(Products::class);
}

public function stock()
{
    return $this->belongsTo(Stocks::class);
}
}
