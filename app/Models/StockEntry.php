<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    /**
     * @var array
     */

	protected $table = 'stock_entries';

	/**
     * @var array
     */
    protected $fillable = ['name'];


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

    public function get_purchase(){

        return $this->belongsTo(\App\Models\PurchaseOrder::class,'transaction_reference_id');
        
    }


    public function get_sales(){

        return $this->belongsTo(\App\Models\Orders::class,'transaction_reference_id');
        
    }

    public function get_invoice(){

        return $this->belongsTo(\App\Models\Invoice::class,'transaction_reference_id','bill_no');
        
    }

    public function locationTransfer(){


        return $this->belongsTo(\App\Models\LocationStockTransfer::class,'transaction_reference_id');

    }



    public function getOrdersBill(){


        if($this->trans_type == PURCHINVOICE)
          return $this->get_purchase->bill_no;
        elseif($this->trans_type == SALESINVOICE)
             return $this->get_sales->outlet->outlet_code.''.$this->get_sales->bill_no;
        elseif ($this->trans_type == STOCKMOVEIN) 
            return 'TRANSIN.'. $this->locationTransfer->id;
        elseif ($this->trans_type == STOCKMOVEOUT) 
            return 'TRANSOUT.'.$this->locationTransfer->id;
        


    }


    public function getUrl(){

        if($this->trans_type == PURCHINVOICE){

            $order = $this->get_purchase;
            $href = $order->id ?  "/admin/purchase/{$order->id}?type={$order->purchase_type}" : null;

        }elseif ($this->trans_type == SALESINVOICE) {
            $order = $this->get_sales;

            $href = $order->id ? "/admin/orders/{$order->id}" : null;
        }elseif ($this->trans_type == OTHERSALESINVOICE) {
            $order = $this->get_invoice;
            $href = $order->id ? "/admin/invoice1/{$order->id}": null;
        }elseif ($this->trans_type == STOCKMOVEIN) {
            $order = $this->locationTransfer;
            $href = "/admin/location/stocktransfer/{$order->id}/edit";
        }elseif ($this->trans_type == STOCKMOVEOUT) {
            $order = $this->locationTransfer;
            $href = "/admin/location/stocktransfer/{$order->id}/edit";
        }else{
            $href = '#';
        }

        return $href;

    }




}
