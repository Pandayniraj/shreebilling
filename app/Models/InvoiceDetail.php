<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_detail';

    protected $fillable = ['id', 'client_id', 'invoice_id', 'product_id', 'price', 'quantity', 'total', 'bill_date', 'date', 'tax', 'tax_amount', 'unit'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\User::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public function units()
    {
        return $this->belongsTo(\App\Models\ProductsUnit::class, 'unit');
    }

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
