<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClockHistory extends Model
{
    /**
     * @var array
     */
    protected $table = 'tbl_clock_history';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'clock_id', 'clockin_edit', 'clockout_edit', 'reason', 'status', 'notify_me', 'view_status', 'clockin_old', 'clockout_old'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function clock()
    {
        return $this->belongsTo(\App\Models\Clock::class);
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
