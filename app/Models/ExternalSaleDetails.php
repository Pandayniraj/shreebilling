<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSaleDetails extends Model
{
    use HasFactory;
    protected $table = 'externalsale_details';
    public $timestamps = false;
    protected $fillable = ['externalsales_id', 'product_id', 'price', 'discount', 'quantity', 'total', 'date',  'tax', 'tax_amount', 'unit'];
}
