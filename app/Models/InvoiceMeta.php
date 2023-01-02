<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class InvoiceMeta extends Model
{
    /**
     * @var array
     */
    protected $table = 'invoice_meta';

    /**
     * @var array
     */
    protected $fillable = ['invoice_id', 'sync_with_ird', 'is_bill_active', 'void_reason', 'cancel_date', 'credit_note_no', 'credit_user_id', 'is_realtime'];
}
