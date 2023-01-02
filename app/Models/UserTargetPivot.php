<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class UserTargetPivot extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_target_pivot';

    protected $fillable = ['user_target_id', 'course_id', 'target'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if (\Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Leads from deletion
        /*if ( (\Auth::user()->id != $this->user_id  && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
            return false;
        } */

        if (! \Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isEnableDisable()
    {
        // Protect the admins and users Leads from enabling disabling
        if (Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isenabled()
    {
        // Protect the admins and users Leads from deletion
        if ((\Auth::user()->id != $this->user_id && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
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
        // Return true if the Lead has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }

    /**
     * Force the Lead to have the given permission.
     *
     * @param $permissionName
     */
    public function forcePermission($permissionName)
    {
        // If the Lead has not been given a the said permission
        if (null == $this->perms()->where('name', $permissionName)->first()) {
            // Load the given permission and attach it to the Lead.
            $permToForce = Permission::where('name', $permissionName)->first();
            $this->perms()->attach($permToForce->id);
        }
    }

    /**
     * Save the inputted users.
     *
     * @param mixed $inputUsers
     *
     * @return void
     */
    public function saveUsers($inputUsers)
    {
        if (! empty($inputUsers)) {
            $this->users()->sync($inputUsers);
        } else {
            $this->users()->detach();
        }
    }

    public function userTarget()
    {
        return $this->belongsTo(\App\Models\UserTarget::class);
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
