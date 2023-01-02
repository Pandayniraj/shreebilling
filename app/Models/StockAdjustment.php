<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class StockAdjustment extends Model
{
    /**
     * @var array
     */

	protected $table = 'stock_adjustment';

	/**
     * @var array
     */
    protected $fillable = ['non_taxable_amount','transaction_date','store_id','reason','status','ledgers_id','vat_type','subtotal','discount_percent','taxable_amount','tax_amount','total_amount','comments','entry_id','approved_by','cost_center_id','department_id'];


    public function store()
    {
        return $this->belongsTo('\App\Models\Store');
    }

        public function outlet()
    {
        return $this->belongsTo('\App\Models\PosOutlets','store_id');
    }


     public function adjustmentreason()
    {
        return $this->belongsTo('\App\Models\AdjustmentReason','reason');
    }

    public function detail()
    {
        return $this->hasMany('\App\Models\StockAdjustmentDetail','adjustment_id');
    }





}
