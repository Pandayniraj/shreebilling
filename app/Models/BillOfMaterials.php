<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterials extends Model
{
    /**
     * @var array
     */
    protected $table = 'billofmaterials';

    /**
     * @var array
     */
    protected $fillable = ['org_id', 'user_id', 'bill_date', 'product', 'status', 'auto_assemble', 'auto_disassemble', 'obsolete', 'total_amount', 'comments'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Client::class, 'supplier_id');
    }

    public function productname()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product');
    }
}
