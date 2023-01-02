<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosMenu extends Model
{

    protected $table = 'pos_menu';

    /**
     * @var array
     */

    protected $fillable = ['outlet_id', 'menu_name', 'enabled'];


    public function isEditable()
    {

        if (!\Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {

        if (!\Auth::user()->hasRole('admins'))
            return false;

        return true;
    }

    public function outlet()
    {
        return $this->belongsTo('\App\Models\PosOutlets');
    }
}
