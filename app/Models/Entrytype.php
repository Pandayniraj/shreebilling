<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrytype extends Model
{
    /**
     * @var array
     */
    protected $table = 'entrytypes';
    /**
     * @var array
     */
    protected $fillable = ['label', 'name', 'description', 'base_type', 'numbering', 'prefix', 'suffix', 'zero_padding', 'restriction_bankcash', 'org_id'];

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
        return $this->belongsTo(\App\Models\COAgroups::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(\App\Models\COAgroups::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
