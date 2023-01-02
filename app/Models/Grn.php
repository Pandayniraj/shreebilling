<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    /**
     * @var array
     */
    protected $table = 'grn';

    /**
     * @var array
     */
    protected $fillable = ['purchase_bill_no', 'supplier_id', 'entry_id', 'return_date', 'purchase_order_date', 'user_id', 'org_id', 'pan_no', 'vat_type', 'status', 'is_renewal', 'into_stock_location', 'subtotal', 'discount_percent', 'taxable_amount', 'tax_amount', 'total_amount', 'comments'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Client::class, 'supplier_id');
    }

    public function organization()
    {
        return $this->belongsTo('\App\Models\organization');
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
}
