<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Challan extends Model
{
    /**
     * @var array
     */
    protected $table = 'challans';

    /**
     * @var array
     */
    protected $fillable = [
        "client_id","challan_no","pan_no","challan_date","ref_no","name","position","user_id","is_renewal","driver_name","license_number","vehicle_owner","vehicle_number","driver_phone","freight_per_quintal","total_freight","advanced","tds","to_pay","custom_items_name","final_total","comment","address"

    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function organization()
    {
        return $this->belongsTo('\App\Models\organization');
    }

    public function challanDetail(){
        return $this->belongsTo(ChallanDetails::class,'challan_id');
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
}
