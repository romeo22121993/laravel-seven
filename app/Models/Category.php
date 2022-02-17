<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'category_name_en',
        'category_id',
        'subcategory_id',
        'category_name_hin',
        'category_slug_en',
        'category_slug_hin',
        'category_icon',
    ];

}
