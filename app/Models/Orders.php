<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    /**
     * @var array
     */
    protected $table = 'fin_orders';

    /**
     * @var array
     */
    protected $fillable = [
        'bill_no', 'user_id', 'source', 'client_id', 'org_id', 'entry_id', 'order_type', 'name', 'position',
         'address', 'from_stock_location', 'payment_status', 'comment', 'ship_date', 'require_date', 'sales_tax',
          'status', 'paid', 'bill_date', 'due_date', 'vat_type', 'taxable_amount', 'tax_amount', 'discount_amount', 'discount_note',
          'total_amount', 'paid_amount', 'subtotal', 'trans_type', 'fiscal_year', 'customer_pan', 'amount',
           'sync_with_ird', 'is_bill_printed', 'is_bill_active', 'printed_time', 'printed_by', 'is_realtime', 'terms',
            'discount_percent', 'is_renewal', 'fiscal_year_id' 
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }
    public function product_details(){

        return $this->hasMany(\App\Models\OrderDetail::class, 'order_id', 'id');
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'client_id');
    }

    public function leadname()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'client_id');
    }

    public function organization()
    {
        return $this->belongsTo('\App\Models\organization');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\ProductLocation::class, 'from_stock_location');
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
