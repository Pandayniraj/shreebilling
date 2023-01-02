<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;

class StockMove extends Model
{
    /**
     * @var array
     */

	protected $table = 'product_stock_moves';

	/**
     * @var array
     */
    protected $fillable = ['master_id','stock_id','order_no', 'trans_type','tran_date','person_id','store_id', 'order_reference','reference','transaction_reference_id','note','qty','price','user_id'];  



		public function user()
		{
				return $this->belongsTo('\App\User');
		}

		public function project()
		{
				return $this->belongsTo('\App\Models\Projects');
		}

        public function store()
        {
                return $this->belongsTo('\App\Models\Store');
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




}
