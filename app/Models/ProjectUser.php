<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    /**
     * @var array
     */
    protected $table = 'project_users';

    protected $fillable = ['project_id', 'user_id'];

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Communication from editing changes
        if (('admins' == $this->full_name) || ('users' == $this->full_name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Communication from deletion
        if (('admins' == $this->full_name) || ('users' == $this->full_name)) {
            return false;
        }

        return true;
    }
}
