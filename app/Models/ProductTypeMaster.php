<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTypeMaster extends Model
{
    /**
     * @var array
     */

	protected $table = 'product_type_masters';

	/**
     * @var array
     */
    protected $fillable = ['purchase_ledger_id','cogs_ledger_id','name','ledger_id','asset_ledger_id','service_ledger_id','purchase_return_ledger_id','sales_return_ledger_id','org_id','enabled','discount_limit',];

	/**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Courses from editing changes
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
        // Protect the admins and users Courses from deletion
        if ( ('admins' == $this->name) || ('users' == $this->name) ) {
            return false;
        }

        return true;
    }

    /**
     * @param $Course
     * @return bool
     */
    public static function isForced($Course)
    {
        if ('users' == $Course->name) {
            return true;
        }

        return false;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Course has is assigned the given permission.
        if ( $this->perms()->where('id' , $perm->id)->first() ) {
            return true;
        }
        // Otherwise
        return false;
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function menu()
    {
        return $this->belongsTo('App\Models\PosMenu');
    }
    public function saleLedger()
    {
        return $this->belongsTo(COALedgers::class,'ledger_id');
    }
    public function purchaseLedger()
    {
        return $this->belongsTo(COALedgers::class,'purchase_ledger_id');
    }
    public function cogsLedger()
    {
        return $this->belongsTo(COALedgers::class,'cogs_ledger_id');
    }
    public function assetLedger()
    {
        return $this->belongsTo(COALedgers::class,'asset_ledger_id');
    }
    public function serviceLedger()
    {
        return $this->belongsTo(COALedgers::class,'service_ledger_id');
    }
    public function salesreturnLedger()
    {
        return $this->belongsTo(COALedgers::class,'sales_return_ledger_id');
    }
    public function purchasereturnLedger()
    {
        return $this->belongsTo(COALedgers::class,'purchase_return_ledger_id');
    }
}
