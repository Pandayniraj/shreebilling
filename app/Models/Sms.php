<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    /**
     * @var array
     */
    protected $table = 'sms';

    protected $fillable = ['recipient', 'uuid', 'message'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1) {
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
        if ((\Auth::user()->id != $this->user_id && \Auth::user()->id != 1)) {
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
        if ((\Auth::user()->id != $this->user_id && \Auth::user()->id != 1)) {
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
        if ((\Auth::user()->id != $this->user_id && \Auth::user()->id != 1)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canChangePermissions()
    {
        // Protect the admins Lead from permissions changes
        if (\Auth::user()->id != $this->user_id) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canChangeMembership()
    {
        // Protect the users Lead from membership changes
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1) {
            return false;
        }

        return true;
    }

    /**
     * @param $Lead
     * @return bool
     */
    public static function isForced($Lead)
    {
        if (\Auth::user()->id != $this->user_id) {
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
}
