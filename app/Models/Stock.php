<?php

namespace App\Models;

use App\Models\Projects;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * @var array
     */
    protected $table = 'assets';

    /**
     * @var array
     */
    protected $fillable = ['stock_sub_category_id', 'project_id', 'item_name', 'unit_price', 'total_stock', 'buying_date',
                            'departments_id', 'types', 'conditions', 'item_model', 'location', 'supplier',
                            'invoice_number', 'unit_salvage_value', 'service_date', 'depreciation_rate',
                            'annual_depreciation', 'accumulated_depreciation',
                            'asset_number', 'fiscal_year_id', 'asset_status', ];

    public function subCategory()
    {
        return $this->belongsTo(\App\Models\StockSubCategory::class, 'stock_sub_category_id', 'stock_sub_category_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class);
    }

    public function deparment()
    {
        return $this->belongsTo(\App\Models\Department::class, 'departments_id', 'departments_id');
    }

    public function fiscalyear()
    {
        return $this->belongsTo(\App\Models\Fiscalyear::class, 'fiscal_year_id');
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
