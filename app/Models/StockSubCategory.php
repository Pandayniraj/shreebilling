<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSubCategory extends Model
{
    /**
     * @var array
     */
    protected $table = 'asset_sub_category';

    /**
     * @var array
     */
    protected $fillable = ['stock_category_id', 'stock_sub_category'];

    public function category()
    {
        return $this->belongsTo(\App\Models\StockCategory::class, 'stock_category_id',
            'stock_category_id');
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
