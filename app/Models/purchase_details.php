<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase_details extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'purchase_price',
        'quantity',
        'sub_total',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
