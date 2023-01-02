<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'product_model';
    protected $fillable = ['product_id', 'model_name'];
}
