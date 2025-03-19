<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'barcode', 'description', 'image'];


    //Generating a random barcode
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->barcode)) {
                $product->barcode = self::generateBarcode();
            }
        });
    }
    public static function generateBarcode()
    {
        do {
            $barcode = rand(100000000000, 999999999999); // Generates a 12-digit barcode
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }
public function category()
{
    return $this->belongsTo(Categories::class);
}

public function productStocks()
{
    return $this->hasMany(ProductsStocks::class);
}


}
