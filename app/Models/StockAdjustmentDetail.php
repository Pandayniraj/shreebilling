<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class StockAdjustmentDetail extends Model
{
    /**
     * @var array
     */

	protected $table = 'stock_adjustment_details';

	/**
     * @var array
     */
    protected $fillable = ['tax_rate','tax_amount','adjustment_id','product_id','price','qty','unit','total'];


    public function Project()
    {
        return $this->belongsTo('\App\Models\Projects');
    }
    public function product()
    {
        return $this->belongsTo('\App\Models\Product');
    }
    public function adjustment()
    {
        return $this->belongsTo('\App\Models\StockAdjustment', 'adjustment_id');
    }






}
