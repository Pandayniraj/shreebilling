<?php

namespace App\Models;

use App\Models\Projects;
use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    /**
     * @var array
     */
    protected $table = 'product_stock_moves';

    /**
     * @var array
     */
    protected $fillable = ['store_id','stock_id', 'order_no','unit_id', 'trans_type', 'tran_date', 'person_id', 'order_reference', 'reference', 'transaction_reference_id', 'note', 'qty', 'price'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    public function unit(){
        return $this->belongsTo(\App\Models\ProductsUnit::class, 'unit_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class);
    }

    public function locationname()
    {
        return $this->belongsTo(\App\Models\ProductLocation::class, 'location');
    }



    public function location_transfer(){

        return $this->belongsTo(\App\Models\ProductLocation::class, 'location');

    }

    public function product_details(){
        return $this->belongsTo(\App\Models\Product::class, 'stock_id', 'id');
    }
    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Intakes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }


    public function get_entries(){

        return $this->belongsTo(\App\Models\Entry::class,'transaction_reference_id');

    }
    public function get_purchase(){

        return $this->belongsTo(\App\Models\PurchaseOrder::class,'transaction_reference_id');

    }


    public function get_sales(){

        return $this->belongsTo(\App\Models\Orders::class,'transaction_reference_id');

    }

    public function get_invoice(){

        return $this->belongsTo(\App\Models\Invoice::class,'transaction_reference_id','id');

    }


}
