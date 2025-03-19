<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stocks extends Model
{
    use HasFactory;

    protected $fillable = ['stock_name', 'stock_number'];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate stock_number if not provided
        static::creating(function ($stock) {
                $stock->stock_number = self::generateStockNumber();
            
        });
    }

    public static function generateStockNumber()
    {
        do {
            $stockNumber = 'STK-' . strtoupper(uniqid());
        } while (self::where('stock_number', $stockNumber)->exists());

        return $stockNumber;
    }

    public function productStocks()
    {
        return $this->hasMany(ProductsStocks::class);
    }
}
