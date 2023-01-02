<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPrint extends Model
{
    use HasFactory;
    protected $table = 'delivery_print';
    protected $fillable = ['deliverynote_id','print_date', 'print_by'];
    public $timestamps = false;
}
