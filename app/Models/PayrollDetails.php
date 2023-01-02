<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class PayrollDetails extends Model
{
    /**
     * @var array
     */

	protected $table = 'payroll_details';

	/**
     * @var array
     */
    protected $guarded=[];

    public function payroll()
    {
    	return $this->belongsTo('App\Models\Payroll','payroll_id');
    }
    public function department()
    {
        return $this->belongsTo('\App\Models\Department');
    }

    public function user()
    {
        return $this->belongsTo('\App\User','user_id');
    }

/**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
        if ( ('admins' == $this->name) || ('users' == $this->name) ) {
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
        if ( ('admins' == $this->name) || ('users' == $this->name) ) {
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
        if ( $this->perms()->where('id' , $perm->id)->first() ) {
            return true;
        }
        // Otherwise
        return false;
    }


    public function paidAllowances(){
        return $this->hasMany(SalaryPaymentAllowance::class,'payroll_detail_id','id');
    }


}
