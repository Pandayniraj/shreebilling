<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSales extends Model
{
    use HasFactory;
    protected $table = 'externalsale';
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'client_name', 'order_type', 'position', 'address','sales_person',
        'ship_date', 'require_date', 'sales_tax', 'status', 'paid', 'bill_date', 'due_date',
        'subtotal', 'discount_amount', 'discount_percent', 'discount_note', 'total_amount', 'org_id', 'customer_pan', 'bill_no', 'fiscal_year', 'fiscal_year_id', 'from_stock_location', 'outlet_id'
    ];
    public function externalsalesdetail()
    {
        return $this->hasMany(\App\Models\ExternalSaleDetails::class, 'externalsales_id', 'id');
    }
}
