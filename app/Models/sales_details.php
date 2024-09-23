<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales_details extends Model
{
    use HasFactory;


    protected $fillable = [
        'sales_id',
        'product_id',
        'sales_price',
        'quantity',
        'discount',
        'sub_total',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
