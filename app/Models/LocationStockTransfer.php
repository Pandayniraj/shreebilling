<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class LocationStockTransfer extends Model
{
    /**
     * @var array
     */
    protected $table = 'location_stock_transfer';

    /**
     * @var array
     */
    protected $fillable = ['status', 'transfer_date', 'source_id', 'destination_id', 'user_id', 'quantity', 'comment'];

    public function source()
    {
        return $this->belongsTo(\App\Models\PosOutlets::class, 'source_id');
    }

    public function destination()
    {
        return $this->belongsTo(\App\Models\PosOutlets::class, 'destination_id');
    }
}
