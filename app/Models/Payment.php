<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * @var array
     */
    protected $table = 'payments';

    /**
     * @var array
     */
    protected $fillable = ['date', 'sale_id', 'return_id', 'purchase_id', 'reference_no', 'transaction_id', 'paid_by', 'cheque_no', 'cc_no', 'cc_holder', 'cc_month', 'cc_year', 'cc_type', 'amount', 'currency', 'attachment', 'type', 'note', 'pos_paid', 'pos_balance', 'approval_code', 'created_by','tds','entry_id',
        'bill_amount'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leadtypes from editing changes
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
        // Protect the admins and users Leadtypes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function paidby()
    {
        return $this->belongsTo(\App\Models\Paymentmethod::class, 'paid_by');
    }

    public function sale()
    {
        return $this->belongsTo(\App\Models\Orders::class, 'sale_id');
    }

    public function purchase()
    {
        return $this->belongsTo(\App\Models\PurchaseOrder::class, 'purchase_id');
    }
}
