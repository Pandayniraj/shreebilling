<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankIncome extends Model
{
    protected $table = 'bank_income';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'income_type', 'amount', 'date_received', 'reference_no', 'received_via', 'description', 'income_account', 'user_id', 'entry_id','tag_id','fiscal_year_id'];

    public function entry()
    {
        return $this->belongsTo(\App\Models\Entry::class, 'entry_id');
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

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function customers()
    {
        return $this->belongsTo(\App\Models\Client::class, 'customer_id');
    }

    public function fiscalyear()
    {
        return $this->belongsTo(\App\Models\Fiscalyear::class, 'fiscal_year_id');
    }

     public function tag()
    {
        return $this->belongsTo(\App\Models\IncomeExpenseCategory::class, 'tag_id');
    }

    public function banckAcc(){
        return $this->belongsTo(\App\Models\BankAccount::class, 'income_account');
        
    }
}
