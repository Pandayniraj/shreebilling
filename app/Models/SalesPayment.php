<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    protected $table = 'payment_history';
    //
    //   /**
    //      * @var array
    //      */
    protected $fillable = ['order_no', 'trans_type', 'stock_id', 'tax_type_id', 'description', 'unit_price', 'qty_sent', 'quantity', 'is_inventory', 'discount_amount'];

    public function updatePayment($reference, $amount)
    {
        $currentAmount = DB::table('sales_orders')->where('reference', $reference)->select('paid_amount')->first();
        $sum = ($currentAmount->paid_amount + $amount);
        DB::table('sales_orders')->where('reference', $reference)->update(['paid_amount' => $sum]);

        return true;
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

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
    }
}
