<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id','product_stock_id', 'quantity', 'unit_price', 'subtotal'];

    public function sale()
{
    return $this->belongsTo(Sales::class, 'sale_id');
}

public function productStock()
{
    return $this->belongsTo(ProductsStocks::class);
}

}
