<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total_amount', 'discount', 'final_amount', 'customer_mobile_number'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function saleItems()
{
    return $this->hasMany(SaleItems::class, 'sale_id');
}



}
