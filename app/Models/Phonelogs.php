<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phonelogs extends Model
{
    /**
     * @var array
     */
    protected $table = 'phonelogs';

    protected $fillable = ['user_id', 'mob_phone', 'lead_id'];

    public function contact()
    {
        return $this->hasOne(\App\Models\Client::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'userid');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Communication from editing changes
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
        // Protect the admins and users Communication from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}
