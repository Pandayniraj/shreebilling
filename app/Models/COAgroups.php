<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COAgroups extends Model
{
    /**
     * @var array
     */
    protected $table = 'coa_groups';
    /**
     * @var array
     */
    protected $fillable = ['parent_id', 'name', 'code', 'affects_gross', 'ledger_type', 'staff_or_company_id', 'type', 'org_id', 'user_id'];

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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
