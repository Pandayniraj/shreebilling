<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiscalyear extends Model
{
    /**
     * @var array
     */
    protected $table = 'fiscalyears';

    /**
     * @var array
     */
    protected $fillable = ['fiscal_year', 'numeric_fiscal_year', 'start_date', 'end_date', 'org_id'];

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
