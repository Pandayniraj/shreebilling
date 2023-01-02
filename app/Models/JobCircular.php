<?php

namespace App\Models;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Model;

class JobCircular extends Model
{
    protected $table = 'job_circular';

    protected $fillable = ['job_title', 'designation_id', 'vacancy_no', 'posted_date', 'employment_type', 'experience', 'age', 'salary_range', 'last_date', 'description', 'status'];

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designations_id');
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
