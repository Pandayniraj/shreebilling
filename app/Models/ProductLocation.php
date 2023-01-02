<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLocation extends Model
{
    /**
     * @var array
     */
    protected $table = 'product_location';

    /**
     * @var array
     */
    protected $fillable = ['loc_code', 'location_name', 'delivery_address', 'enabled', 'email', 'phone', 'org_id', 'contact_person'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Courses from editing changes
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
        // Protect the admins and users Courses from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @param $Course
     * @return bool
     */
    public static function isForced($Course)
    {
        if ('users' == $Course->name) {
            return true;
        }

        return false;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Course has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }
}
