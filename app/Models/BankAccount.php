<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_account';

    /**
     * @var array
     */
    protected $fillable = ['created_by', 'account_name', 'account_code', 'currency', 'account_number', 'bank_name', 'routing_number', 'description', 'ledger_id', 'org_id'];

    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }

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
}
