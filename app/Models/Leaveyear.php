<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaveyear extends Model
{
    /**
     * @var array
     */
    protected $table = 'leaveyears';

    /**
     * @var array
     */
    protected $fillable = ['leave_year', 'user_id', 'start_date', 'end_date', 'org_id'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
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
        // Protect the admins and users Leads from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
