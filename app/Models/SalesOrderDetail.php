<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    protected $table = 'sales_order_details';
    //
    //   /**
    //      * @var array
    //      */
    protected $fillable = ['order_no', 'trans_type', 'stock_id', 'tax_type_id', 'description', 'unit_price', 'qty_sent', 'quantity', 'is_inventory', 'discount_amount'];
}
