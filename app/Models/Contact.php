<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * @var array
     */
    protected $table = 'contacts';

    protected $fillable = ['client_id', 'salutation', 'full_name', 'position', 'department', 'email_1', 'email_2', 'phone', 'landline', 'address', 'city', 'postcode', 'country', 'website', 'facebook', 'file', 'enabled', 'phone_2', 'skype', 'user_id', 'description', 'dob', 'photo_url', 'org_id','tag_id'];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }

    public function tag()
    {
        return $this->belongsTo(\App\Models\Contacttag::class);
    }

    public function locations()
    {
        return $this->belongsTo(\App\Models\CityMaster::class, 'city');
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
