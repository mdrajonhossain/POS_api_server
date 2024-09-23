<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'supplier_id',
        'total_item',
        'total_price',
        'discount',
        'total_pay'
    ];

    

    // public function supplier(){
    //     return $this->hasOne(Supplier::class);
    // }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    

}