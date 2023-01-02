<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class ProductInternationPurchase extends Model
{
    /**
     * @var array
     */
    protected $table = 'purchase_international';

    /**
     * @var array
     */
    protected $fillable = ['product_id','excise_charge_percentage_per_unit', 'bank_commission_percentage_per_unit', 'agent_commission_per_unit', 'transportation_charge_per_unit', 'insurence_charge_per_unit','warehouse_charge_per_unit'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class , 'product_id');
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
