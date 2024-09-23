<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'nama',
        'address',
        'phone'
    ]; 



    // public function purchase(){
    //     return $this->belongsTo(purchase::class);
    // }

    public function purchase(){
        return $this->hasOne(Purchase::class);
    }


}
