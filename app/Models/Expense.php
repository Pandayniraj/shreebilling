<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * @var array
     */
    protected $table = 'expenses';

    protected $fillable = ['user_id', 'expenses_account', 'amount', 'paid_through', 'vendor_id', 'date', 'reference', 'attachment', 'org_id', 'project_id','tag_id','entry_id', 'expense_type', 'fiscal_year_id'];

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Client::class, 'vendor_id');
    }

    public function paidledger()
    {
        return $this->belongsTo(\App\Models\COALedgers::class, 'paid_through', 'id');
    }

    public function ledger()
    {
        return $this->belongsTo(\App\Models\COALedgers::class, 'expenses_account', 'id');
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class, 'project_id');
    }

    public function tag()
    {
        return $this->belongsTo(\App\Models\IncomeExpenseCategory::class, 'tag_id');
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


        public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}

