<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition_Detail extends Model
{


    /**
     * @var array
     */
    protected $table='requisition_details';
    protected $fillable = ['requisition_id', 'product_id','unit', 'quantity', 'reason'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

}
