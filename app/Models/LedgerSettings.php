<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class LedgerSettings extends Model
{
    protected $table = 'ledger_setting';

    protected $fillable = ['org_id', 'ledger_name', 'ledger_id', 'table_name', 'is_default',
    'description', ];

    public function coaledgerGroup()
    {
        return $this->belongsTo(\App\Models\COAgroups::class, 'ledger_id', 'id');
    }

    public function coaledgers()
    {
        return $this->belongsTo(\App\Models\COALedgers::class, 'ledger_id', 'id');
    }

    public function ledgername()
    {
        if ($this->table_name == 'coa_groups') {
            return $this->belongsTo(\App\Models\COAgroups::class, 'ledger_id', 'id');
        } else {
            return $this->belongsTo(\App\Models\COALedgers::class, 'ledger_id', 'id');
        }
    }

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
}
