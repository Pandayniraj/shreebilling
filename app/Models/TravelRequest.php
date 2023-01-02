<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    protected $fillable = ['place_of_visit', 'depart_date', 'arrival_date', 'num_days', 'travel_cost', 'is_billable_to_customer', 'customer_name', 'status', 'visit_purpose', 'remarks', 'staff_id', 'business_account', 'user_id', 'phone_num'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if (\Auth::check() && \Auth::user()->hasRole('admins')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Leads from deletion
        // if ( (\Auth::user()->id != $this->user_id  && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
        //     return false;
        // }

        if (! \Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'business_account');
    }
}
