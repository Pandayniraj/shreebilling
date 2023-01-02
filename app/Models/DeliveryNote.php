<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;
    protected $fillable = ['sales_bill_no', 'client_id', 'entry_id', 'delivery_note_date', 'sales_bill_date', 'user_id', 'org_id', 'pan_no', 'vat_type', 'status', 'is_renewal', 'into_stock_location', 'subtotal', 'discount_percent', 'taxable_amount', 'tax_amount', 'total_amount', 'comments'];
    public $timestamps = false;
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }
    public function notedetails()
    {
        return $this->hasMany(\App\Models\DeliveryNoteDetails::class, 'deliverynote_id', 'id');
    }
}
