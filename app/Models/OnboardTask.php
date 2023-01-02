<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardTask extends Model
{
    protected $table = 'onboard_tasks';

    /**
     * @var array
     */
    protected $fillable = ['event_id', 'participants', 'task_owner', 'name', 'description', 'notify_mail', 'due_date', 'effective_date', 'priority', 'user_id', 'notified_before'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(\App\Models\OnboardEvents::class, 'event_id');
    }

    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'task_owner');
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
