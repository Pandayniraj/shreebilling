<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * @var array
     */
    protected $table = 'tbl_attendance';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'org_id', 'leave_category_id', 'date_in', 'date_out', 'attendance_status', 'clocking_status'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function clock()
    {
        $first = \App\Models\Clock::where('attendance_id', $this->attendance_id)
                ->orderBy('created_at', 'asc')
                ->first();
        $time = $this->date_in.' '.$first->clockin_time;

        return $time;
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
