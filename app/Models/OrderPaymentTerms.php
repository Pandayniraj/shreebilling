<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentTerms extends Model
{
    /**
     * @var array
     */
    protected $table = 'fin_order_payment_terms';

    /**
     * @var array
     */
    protected $fillable = ['order_id', 'term_date', 'term_condition', 'term_amount', 'status','is_client_notified'];

   

    public function orders()
    {
        return $this->belongsTo(\App\Models\Orders::class, 'order_id');
    }
}
