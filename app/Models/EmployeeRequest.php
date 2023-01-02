<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    /**
     * @var array
     */
    protected $table = 'emp_requests';

    /**
     * @var array
     */
    protected $fillable = ['emp_id', 'title', 'request_type', 'benefit_type', 'pay_type',
    'cost', 'description', 'status', 'attachment', 'request_team', 'comment', 'approved_by', ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'emp_id');
    }

    public function approvedUser()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'request_team');
    }

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
}
