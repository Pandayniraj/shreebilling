<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    /**
     * @var array
     */
    protected $table = 'tbl_training';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'assigned_by', 'training_name', 'vendor_name', 'start_date', 'finish_date', 'training_cost', 'status', 'performance', 'remarks', 'upload_file', 'permission', 'org_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(\App\User::class, 'assigned_by');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->user_id || Auth::user()->id == $this->assigned_by || Auth::user()->id == 1)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && Auth::user()->hasRole('admins')) {
            return true;
        }

        return false;
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
