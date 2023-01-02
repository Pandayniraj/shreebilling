<?php

namespace App\Models;

use App\Models\JobCircular;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $fillable = ['job_circular_id', 'name', 'email', 'mobile', 'cover_letter', 'resume', 'application_status'];

    public function circular()
    {
        return $this->belongsTo(JobCircular::class, 'job_circular_id', 'job_circular_id');
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
