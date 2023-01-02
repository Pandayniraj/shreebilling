<?php

namespace App\Models;

use App\Models\Projectscat;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    /**
     * @var array
     */
    protected $table = 'projects';

    /**
     * @var array
     */
    protected $fillable = ['name', 'assign_to', 'description', 'start_date', 'end_date', 'status', 'enabled', 'staffs', 'org_id', 'projects_cat', 'class', 'tagline', 'website1', 'website2', 'website3', 'facebook1', 'facebook2', 'youtube'];

    public function assigned()
    {
        return $this->belongsTo(\App\User::class, 'assign_to');
    }

    public function projectcategory()
    {
        return $this->belongsTo(Projectscat::class, 'projects_cat', 'id');
    }

    public function project_users()
    {
        return $this->hasMany(\App\Models\ProjectUser::class, 'project_id', 'id');
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
