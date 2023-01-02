<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerialNumber extends Model
{
    protected $table = 'product_serial_number';
    protected $fillable = ['product_id', 'serial_num', 'model_id'];

    public function product_model()
    {
        return $this->belongsTo(\App\Models\ProductModel::class, 'model_id', 'id');
    }
}
