<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class LocationStockTransferDetail extends Model
{
    /**
     * @var array
     */
    protected $table = 'location_stock_transfer_detail';

    /**
     * @var array
     */
    protected $fillable = ['location_stock_transfer_id', 'product_id', 'quantity', 'reason'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
