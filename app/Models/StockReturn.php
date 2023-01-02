<?php

namespace App\Models;

use App\Models\Projects;
use App\Models\Stock;
use App\User;
use Illuminate\Database\Eloquent\Model;

class StockReturn extends Model
{
    /**
     * @var array
     */
    protected $table = 'asset_return';

    /**
     * @var array
     */
    protected $fillable = ['stock_id', 'user_id', 'return_inventory', 'return_date', 'project_id'];

    public function stock()
    {
        return $this->belongsTo(\App\Models\Stock::class, 'stock_id', 'stock_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class);
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
