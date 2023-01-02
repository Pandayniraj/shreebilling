<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purch_order_details';

    protected $fillable = ['tax_amount','tds_amount','tds_rate','unitpricewithimport','po_detail_item', 'order_no', 'product_id', 'description', 'qty_invoiced', 'unit_price', 'tax_type_id', 'quantity_ordered', 'quantity_recieved', 'suplier_id', 'total', 'is_inventory', 'units', 'discount', 'rmb', 'freight', 'custom', 'transport', 'warehouse', 'bankcharge', 'insurance', 'commission', 'unit_total'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\User::class, 'customer_id');
    }

//    public function order()
//    {
//        return $this->belongsTo('App\Models\Order', 'order_id');
//    }

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
}
