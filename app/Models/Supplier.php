<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * @var array
     */
    protected $table = 'suppliers';

    protected $fillable = ['name', 'location', 'vat', 'phone', 'email', 'website', 'industry', 'stock_symbol', 'type', 'enabled', 'org_id'];

    public function contact()
    {
        return $this->hasOne(\App\Models\Client::class);
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
