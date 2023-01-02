<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['lead_id', 'task_subject', 'task_detail', 'task_status', 'task_owner', 'task_assign_to', 'task_priority', 'task_start_date', 'task_due_date', 'task_complete_percent', 'task_alert', 'enabled', 'org_id', 'color', 'synced_with_google'];

    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'task_owner');
    }

    public function assigned_to()
    {
        return $this->belongsTo(\App\User::class, 'task_assign_to');
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Courses from editing changes
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
        // Protect the admins and users Courses from deletion
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
        // Return true if the Course has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
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
}
