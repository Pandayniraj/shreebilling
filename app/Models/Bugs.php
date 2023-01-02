<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bugs extends Model
{
    /**
     * @var array
     */
    protected $table = 'bugs';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'project_id', 'priority', 'category', 'fixed_in_release', 'found_in_release', 'status', 'type', 'subject', 'description', 'resolution', 'viewed', 'source', 'assigned_to', 'enabled'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function assigned()
    {
        return $this->belongsTo(\App\User::class, 'assigned_to');
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
