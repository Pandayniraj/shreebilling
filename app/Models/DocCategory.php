<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocCategory extends Model
{
    protected $table = 'doc_categories';

    protected $fillable = ['name', 'description', 'user_id', 'org_id', 'color'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
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
