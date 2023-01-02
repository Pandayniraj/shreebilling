<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @var array
     */
    protected $table = 'products';

    /**
     * @var array
     */
    protected $fillable = ['name', 'cost', 'price', 'store_id', 'enabled', 'public', 'category_id', 'org_id', 'ordernum', 'alert_qty', 'product_code', 'sku', 'product_unit', 'product_image', 'ledger_id', 'is_fixed_assets','type','warranty','agent_price','is_raw_material','product_division', 'product_type_id'];
//'purchase_ledger_id','cogs_ledger_id',
    public function category()
    {
        return $this->belongsTo(\App\Models\ProductCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\ProductsUnit::class, 'product_unit');
    }
    // public function punits()
    // {
    //     return $this->belongsTo(\App\Models\ProductsUnit::class, 'product_unit');
    // }
    public function purchase_international()
    {
        return $this->hasOne(\App\Models\ProductInternationPurchase::class,'product_id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
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
        // Protect the admins and users Courses from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @param $Course
     * @return bool
     */
    public static function isForced($Course)
    {
        if ('users' == $Course->name) {
            return true;
        }

        return false;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Course has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
