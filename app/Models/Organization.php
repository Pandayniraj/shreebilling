<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /**
     * @var array
     */
    protected $table = 'organizations';

    protected $fillable = ['name', 'organization_name', 'vat_id', 'address', 'phone', 'email', 'logo', 'stamp', 'enabled','website','login_bg'];

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
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
