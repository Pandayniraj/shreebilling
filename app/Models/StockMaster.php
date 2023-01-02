<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMaster extends Model
{
    /**
     * @var array
     */

	protected $table = 'product_stock_master';

	/**
     * @var array
     */
    protected $guarded = [];




	public function user()
	{
			return $this->belongsTo('\App\User');
	}

    public function stockentrytype()
    {
            return $this->belongsTo('\App\Models\StockEntry','stock_entry_id');
    }

        public function reason()
    {
            return $this->belongsTo('\App\Models\AdjustmentReason');
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
