<?php

namespace App\Models;

use App\Models\Projects;
use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    /**
     * @var array
     */
    protected $table = 'product_stock_moves';

    /**
     * @var array
     */
    protected $fillable = ['stock_id', 'order_no', 'trans_type', 'tran_date', 'person_id', 'location', 'order_reference', 'reference', 'transaction_reference_id', 'note', 'qty', 'price', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class);
    }

    public function locationname()
    {
        return $this->belongsTo(\App\Models\ProductLocation::class, 'location');
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
