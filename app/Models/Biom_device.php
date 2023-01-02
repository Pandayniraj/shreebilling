<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biom_device extends Model
{
    protected $fillable = [
        'device_name',
        'ip_address',
        'com_key',
        'serial_number',
    ];
    public $timestamps = true;
    //


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
