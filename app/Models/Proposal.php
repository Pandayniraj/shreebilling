<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /**
     * @var array
     */
    protected $table = 'proposals';

    /**
     * @var array
     */
    protected $fillable = ['client_lead_id', 'contact_id', 'product_id', 'type', 'body', 'subject', 'enabled', 'template', 'status', 'org_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'client_lead_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    /**
     * @return bool
     */
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

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }
}
