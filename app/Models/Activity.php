<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * @var array
     */
    protected $table = 'activity';

    /**
     * @var array
     */
    protected $fillable = ['code', 'project_id', 'start_date', 'end_date', 'other_details'];

    public function Project()
    {
        return $this->belongsTo(\App\Models\Projects::class);
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
