<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterialsDetail extends Model
{
    /**
     * @var array
     */
    protected $table = 'billofmaterials_details';

    /**
     * @var array
     */
    protected $fillable = ['billofmaterials_id', 'product_id', 'units', 'quantity', 'wastage_qty', 'cost_price', 'total'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function unitname()
    {
        return $this->belongsTo(\App\Models\ProductsUnit::class, 'units');
    }

    public function organization()
    {
        return $this->belongsTo('\App\Models\organization');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
