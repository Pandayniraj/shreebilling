<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftBreak extends Model
{
    protected $table = 'shift_breaks';

    /**
     * @var array
     */
    protected $fillable = ['shift_id', 'name', 'icon', 'start_time', 'end_time', 'pay_type'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'shift_id');
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
