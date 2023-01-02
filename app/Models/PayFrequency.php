<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayFrequency extends Model
{
    /**
     * @var array
     */
    protected $table = 'pay_frequency';

    /**
     * @var array
     */
    protected $fillable = ['frequency', 'check_date', 'period_start_date', 'period_end_date', 'user_id', 'is_issued', 'time_entry_method', 'name', 'fiscal_year_id'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leadtypes from editing changes
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
        // Protect the admins and users Leadtypes from deletion
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
