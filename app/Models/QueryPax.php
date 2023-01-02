<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueryPax extends Model
{
    /**
     * @var array
     */
    protected $table = 'lead_query_pax';

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['lead_query_id', 'pax_name', 'mileage_card'];

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
