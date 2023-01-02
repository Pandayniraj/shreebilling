<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadClosureReason extends Model
{
    protected $table = 'lead_closure_reason';
    protected $fillable = ['user_id', 'org_id', 'reason'];

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Communication from editing changes
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
        // Protect the admins and users Communication from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}
