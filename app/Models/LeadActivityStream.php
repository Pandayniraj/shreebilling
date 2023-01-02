<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class LeadActivityStream extends Model
{
    /**
     * @var array
     */
    protected $table = 'lead_activity_stream';

    /**
     * @var array
     */
    protected $fillable = ['lead_id', 'column_name', 'related_status_or_rating_id', 'activity', 'user_id', 'icons', 'change_type', 'color', 'task_assigned_to'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function assigned_to()
    {
        return $this->belongsTo(\App\User::class, 'task_assigned_to');
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
