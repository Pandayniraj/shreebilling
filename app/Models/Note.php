<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * @var array
     */
    protected $table = 'lead_notes';

    /**
     * @var array
     */
    protected $fillable = ['note', 'lead_id', 'user_id'];

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

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
    }
}
