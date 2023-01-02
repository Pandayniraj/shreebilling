<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    /**
     * @var array
     */
    protected $table = 'timesheet';

    /**
     * @var array
     */
    protected $fillable = ['employee_id', 'date', 'time_from', 'time_to', 'comments', 'activity_id', 'project_id', 'date_submitted','org_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(\App\User::class, 'employee_id');
    }

    public function activity()
    {
        return $this->belongsTo(\App\Models\Activity::class);
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
