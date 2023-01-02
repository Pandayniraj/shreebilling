<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNoteDetails extends Model
{
    use HasFactory;
    protected $table = 'delivery_note_details';
    protected $fillable = ['deliverynote_id', 'product_id', 'unit', 'sales_quantity', 'invoiced_quantity', 'sales_price', 'return_price', 'return_total',  'reason'];
    public $timestamps = false;
}
