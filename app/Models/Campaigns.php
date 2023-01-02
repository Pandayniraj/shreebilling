<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'currency', 'budget', 'expected_cost', 'actual_cost', 'expected_revenue', 'campaign_type', 'objective', 'content', 'enabled', 'camp_status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany(\App\Models\Lead::class, 'campaign_id');
    }

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
}
