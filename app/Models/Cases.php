<?php

namespace App\Models;

use App\Models\Projects;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    /**
     * @var array
     */
    protected $table = 'cases';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'lead_id', 'org_id', 'priority', 'status', 'type', 'subject', 'description', 'attachment', 'resolution', 'ticket_name', 'ticket_email', 'viewed', 'assigned_to', 'enabled', 'client_id', 'job_no', 'cal_date', 'cust_name', 'product', 'address', 'model_no', 'city', 'serial_no', 'telephone', 'dop', 'do_amc', 'preff_d_t', 'dealer_name', 'sys_status', 'cust_req', 'prob_obs', 'action_taken', 'cust_comments', 'payment_details', 'total_amount', 'labour', 'transport', 'amc', 'tax', 'net_total', 'total_amount_rem', 'labour_rem', 'transport_rem', 'amc_rem', 'tax_rem', 'net_total_rem', 'latitude', 'longitude', 'signature', 'signature_array'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class);
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'lead_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function dealer()
    {
        return $this->belongsTo(\App\Models\Client::class, 'dealer_name', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function assigned()
    {
        return $this->belongsTo(\App\User::class, 'assigned_to');
    }

    public function products()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product', 'id');
    }

    public function modelNum()
    {
        return $this->belongsTo(\App\Models\ProductModel::class, 'model_no', 'id');
    }

    public function serialNum()
    {
        return $this->belongsTo(\App\Models\ProductSerialNumber::class, 'serial_no', 'id');
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
