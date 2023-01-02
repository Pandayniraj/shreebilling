<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOutlets extends Model
{

    protected $table = 'pos_outlets';

    /**
     * @var array
     */

    protected $fillable = ['outlet_code', 'name', 'short_name', 'enabled', 'outlet_type', 'fnb_outlet', 'ledger_id', 'bill_printer', 'kot_printer', 'bill_printer_port', 'kot_printer_port', 'bot_printer', 'bot_printer_port'];


    public function outlet()
    {
        return $this->belongsTo('\App\Models\PosOutlets');
    }


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
}
