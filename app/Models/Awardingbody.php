<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Awardingbody extends Model
{
    protected $table = 'course_awarding_body';

    protected $fillable = ['title', 'enabled'];

    public function course()
    {
        return $this->hasMany('App\Models\Course');
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
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1) {
            return false;
        }

        return true;
    }

    /**
     * @param $Course
     * @return bool
     */
    public static function isForced($Course)
    {
        if ('users' == $Course->name) {
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
        // Return true if the Course has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }
}
