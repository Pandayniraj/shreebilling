<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assembly extends Model
{
    /**
     * @var array
     */
    protected $table = 'assembly';

    /**
     * @var array
     */
    protected $fillable = ['org_id', 'user_id', 'product', 'status', 'total_amount', 'source', 'destination', 'assembled_quantity', 'can_assemble_qty', 'can_assemble_qty_all_levels', 'total_cost', 'assemble_by', 'comments'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function sourcename()
    {
        return $this->belongsTo(\App\Models\ProductLocation::class, 'source');
    }

    public function destinationname()
    {
        return $this->belongsTo(\App\Models\ProductLocation::class, 'destination');
    }

    public function productname()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product');
    }
}
