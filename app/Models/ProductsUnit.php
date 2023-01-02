<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsUnit extends Model
{
    protected $table = 'product_units';
    protected $fillable = ['name', 'symbol','qty_count'];
}
