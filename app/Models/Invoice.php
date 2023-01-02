<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * @var array
     */
    protected $table = 'invoice';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'client_id', 'order_type', 'name', 'position', 'address','sales_person',
        'ship_date', 'require_date', 'sales_tax', 'status', 'paid', 'bill_date', 'due_date',
        'subtotal', 'discount_amount', 'discount_percent', 'discount_note', 'total_amount',
        'comment', 'org_id', 'customer_pan', 'tax_amount', 'terms', 'taxable_amount', 'bill_no', 'fiscal_year', 'is_renewal', 'fiscal_year_id', 'from_stock_location', 'entry_id','outlet_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class ,'client_id');
    }

    public function entry()
    {
        return $this->belongsTo(\App\Models\Entry::class, 'entry_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\PosOutlets::class);
    }

    public function organization()
    {
        return $this->belongsTo('\App\Models\organization');
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'client_id');
    }

    public function invoicemeta()
    {
        return $this->hasOne(\App\Models\InvoiceMeta::class, 'invoice_id', 'id');
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
    public function invoiceDetail(){
        return $this->hasMany(InvoiceDetail::class,'invoice_id');
    }
    public function deliverynote(){
        return $this->hasOne(\App\Models\DeliveryNote::class,'sales_bill_no','id');
    }
}
