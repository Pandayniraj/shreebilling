<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable;
    /**
     * @var array
     */
    protected $table = 'clients';

    protected $fillable = ['name', 'location', 'vat', 'phone', 'email', 'website', 'industry', 'stock_symbol', 'type', 'enabled', 'org_id', 'ledger_id', 'notes', 'reminder', 'bank_name', 'bank_branch', 'bank_account', 'relation_type', 'physical_address', 'customer_group','image'];

    public function contact()
    {
        return $this->hasOne(self::class);
    }

    public function locations()
    {
        return $this->belongsTo(\App\Models\CityMaster::class, 'location');
    }

     public function ledger()
    {
        return $this->belongsTo(\App\Models\COALedgers::class,'ledger_id');
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
    public function products(){
        return $this->hasMany(Product::class,'supplier_id');
    }
    public function groups()
    {
        return $this->belongsTo(\App\Models\CustomerGroup::class, 'customer_group');
    }

}
