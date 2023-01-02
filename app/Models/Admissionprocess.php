<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admissionprocess extends Model
{
    /**
     * @var array
     */
    protected $table = 'admission_process';

    /**
     * @var array
     */
    protected $fillable = ['name', 'enabled'];

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

    public function lead()
    {
        return $this->hasOne(\App\Models\Lead::class);
    }
}
