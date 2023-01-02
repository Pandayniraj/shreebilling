<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chalani extends Model
{
    protected $table = 'chalani';

    /**
     * @var array
     */
    protected $fillable = ['date', 'letter_num', 'letter_date', 'subject', 'content', 'receiver_org', 'ticket_id', 'remarks', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id', 'id');
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
