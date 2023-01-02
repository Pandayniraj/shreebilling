<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COALedgers extends Model
{
    /**
     * @var array
     */
    protected $table = 'coa_ledgers';
    /**
     * @var array
     */
    protected $fillable = ['group_id','affect_stock', 'name', 'code', 'op_balance', 'op_balance_dc', 'type', 'reconciliation', 'notes', 'org_id', 'user_id'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leadtypes from editing changes
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
        // Protect the admins and users Leadtypes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }


    public function clients()
    {
        return $this->hasOne(\App\Models\Client::class,'id','ledger_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function lead()
    {
        return $this->hasOne(\App\Models\Lead::class);
    }
    public function group(){
        return $this->belongsTo(COAgroups::class,'group_id');
    }
}
