<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'product_code',
        'brand_id',
        'purchase_price',
        'selling_price',
        'discount',
        'stock',
    ];

    public function purchaseDetail(){
        return $this->hasOne(PurchaseDetail::class);
    }

    public function sales_details(){
        return $this->hasOne(SalesDetails::class);
    }

}
