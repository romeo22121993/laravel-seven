<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class MultiImg extends Model
{
    use HasFactory;

    protected $table = 'multi_imgs';
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

}
