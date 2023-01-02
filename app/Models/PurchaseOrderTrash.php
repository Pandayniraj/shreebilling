<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderTrash extends Model
{
    /**
     * @var array
     */
    protected $table = 'purch_orders_trash';

    /**
     * @var array
     */
    protected $fillable = ['id', 'org_id', 'supplier_id', 'project_id', 'bill_no', 'purchase_type', 'user_id', 'comments', 'ord_date', 'due_date', 'bill_date', 'delivery_date', 'reference', 'status', 'vat_type', 'pan_no', 'into_stock_location', 'subtotal', 'discount_percent', 'discount_amount', 'non_taxable_amount', 'total_tax_amount', 'taxable_amount', 'tax_amount', 'currency', 'total', 'payment_status', 'ledger_id', 'fiscal_year', 'entry_id', 'is_renewal', 'fiscal_year_id', 'discount_type', 'supplier_type','delete_by'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    public function deleteby()
    {
        return $this->belongsTo(\App\User::class,'delete_by','id');
    }

    public function client()
    {
        if ($this->supplier_type == 'cash_equivalent') {
            return $this->belongsTo(\App\Models\COALedgers::class, 'supplier_id');
        } else {
            return $this->belongsTo(\App\Models\Client::class, 'supplier_id');
        }
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'client_id');
    }

    public function entry()
    {
        return $this->belongsTo(\App\Models\Entry::class, 'entry_id');
    }

    public function organization()
    {
        return $this->belongsTo('\App\Models\organization');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\ProductsUnit::class, 'product_unit');
    }

    public function get_fiscal_year()
    {
        return $this->belongsTo(\App\Models\Fiscalyear::class, 'fiscal_year_id', 'id');
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
    public function product_details(){

        return $this->hasMany(\App\Models\PurchaseOrderDetail::class, 'order_no', 'id');
    }
}
