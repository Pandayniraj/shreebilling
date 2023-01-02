<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clock extends Model
{
    /**
     * @var array
     */
    protected $table = 'tbl_clock';

    /**
     * @var array
     */
    protected $fillable = ['attendance_id', 'clockin_time', 'clockout_time', 'comments', 'clocking_status', 'time_changed', 'ip_address', 'in_device', 'out_device'];

    public function attendance()
    {
        return $this->belongsTo(\App\Models\Attendance::class, 'attendance_id');
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
