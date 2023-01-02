<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    /**
     * @var array
     */
    protected $table ='entries';
//    public function __construct($table_name=null)
//    {
//        if ($table_name)
//            $this->setTable($table_name);
//    }

    /**
     * @var array
     */
    protected $fillable = ['image','tag_id', 'entrytype_id', 'number', 'date', 'dr_total', 'cr_total', 'notes', 'source', 'org_id', 'user_id', 'fiscal_year_id','currency','order_id','resv_id','bill_no', 'payment_month','is_approved','approved_by','remarks','ref_id','payment_month','do'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leadtypes from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    public function fiscalyear()
    {
        return $this->belongsTo(\App\Models\Fiscalyear::class, 'fiscal_year_id');
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

    public function entry_items(){

        return $this->hasMany('App\Models\Entryitem', 'entry_id', 'id');

    }


    public function dynamicEntryItems()
    {
        $entry_item_table=new Entryitem();
        $prefix='';

        if (\Request::get('fiscal_year')){
            $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
            $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
            if ($fiscal_year!=$current_fiscal->fiscal_year){
                $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
                $new_item_table=$prefix.'entryitems';
                $entry_item_table->setTable($new_item_table);
            }
        }
        return $entry_item_table->where('entry_id',$this->id)->get();
    }
    public function lead()
    {
        return $this->hasOne(\App\Models\Lead::class);
    }

    public function entrytype()
    {
        return $this->belongsTo(\App\Models\Entrytype::class);
    }
       public function order()
    {

        if($this->resv_id){

            return $this->belongsTo(\App\Models\Orders::class,'resv_id','reservation_id');
        }


        return $this->belongsTo(\App\Models\Orders::class,'order_id');
    }

    public function tagname()
    {
        return $this->belongsTo(\App\Models\Tag::class, 'tag_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\COAgroups::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(\App\Models\COAgroups::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function billNum(){
        if($this->bill_no!=null && $this->bill_no){
            return $this->bill_no;
        }
        if($this->order!=null && $this->order){
        return $this->order->outlet->outlet_code.''.$this->order->bill_no;
        }else
        {
            return null;
        }
    }
    public function dynamicOrder(){

        $order_table=new Orders();
        $prefix='';

        if (\Request::get('fiscal_year')){
            $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
            // $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
        $fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;
            if ($fiscal_year!=$current_fiscal->numeric_fiscal_year){
                $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
                $new_order=$prefix.'fin_orders';
                $order_table->setTable($new_order);
            }
        }

        $order=$order_table->find($this->order_id);
        return $order;

    }


    public function dynamicSessionOrder(){
        $order_table=new Orders();
        $prefix='';

        if (\Session::get('selected_fiscal_year')){
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;
            if ($selected_fiscal_year!=$current_fiscal_year->numeric_fiscal_year){
                $prefix=$selected_fiscal_year.'_';
                $new_order=$prefix.'fin_orders';
                $order_table->setTable($new_order);
            }
        }

        // $order=$this->resv_id?$order_table->where('reservation_id',$this->resv_id)->first():$order_table->find($this->order_id);
        $order=$order_table->find($this->order_id);
        return $order;
    }
    public function dynamicBillNum(){
            
             if($this->bill_no!=null && $this->bill_no){
            return $this->bill_no;
            }
            if($this->dynamicOrder()!=null && $this->dynamicOrder()){
                return $this->dynamicOrder()->outlet->outlet_code??'' .''.$this->dynamicOrder()->bill_no??'' ;
            }else{
                return null;
            }

    }


    public function dynamicSessionBillNum(){

        if($this->bill_no!=null && $this->bill_no){
            return $this->bill_no;
        }
        if($this->dynamicSessionOrder()!=null && $this->dynamicSessionOrder()){
                return $this->dynamicSessionOrder()->outlet->outlet_code??'' .''.$this->dynamicSessionOrder()->bill_no??'';
        }else{
            return null;
        }

    }
    public function getDynamicEntryType(){


        if($this->dynamicOrder()){


            return ['type'=>'Sales Invoice','order'=>$this->dynamicOrder()];

        }
        if($this->purchase){


            return ['type'=>'Purhase Bills','order'=>$this->purchase];

        }
    }
    public function getEntryType(){


        if($this->orders){


            return ['type'=>'Sales Invoice','order'=>$this->orders];

        }
        if($this->purchase){


            return ['type'=>'Purhase Bills','order'=>$this->purchase];

        }
    }
    public function checkLedger()
    {
        $entry_item_table=new \App\Models\Entryitem();
        $prefix='';

        if (\Session::get('selected_fiscal_year')){
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;
            if ($selected_fiscal_year!=$current_fiscal_year->numeric_fiscal_year){
                $prefix=$selected_fiscal_year.'_';
                $new_entry_item_table=$prefix.'entryitems';
                $entry_item_table->setTable($new_entry_item_table);
            }
        }
        $items = $entry_item_table->select('ledger_id','amount','dc')->where('entry_id',$this->id)->get();
        $cr = $items->where('dc','C')->sum('amount');
        $dr = $items->where('dc','D')->sum('amount');
//        return $cr.'-'.$dr.'-->'.($dr-$cr);
        if(round($cr,2) != round($dr,2))
        {
            return "<span class='text-yellow'>Entry Missing !!</span>";
        }
       foreach($items as $key=>$value)
       {
           if(!$value->ledgerdetail)
           {
               return "<span class='text-yellow'>Ledger Missing !!</span>";
           }

       }
        return '';

    }

    public function getEntryDifferenceAttribute(){
        $entry_item_table=new \App\Models\Entryitem();
        $prefix='';

        if (\Session::get('selected_fiscal_year')){
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;
            if ($selected_fiscal_year!=$current_fiscal_year->numeric_fiscal_year){
                $prefix=$selected_fiscal_year.'_';
                $new_entry_item_table=$prefix.'entryitems';
                $entry_item_table->setTable($new_entry_item_table);
            }
        }
        $items = $entry_item_table->select('ledger_id','amount','dc')->where('entry_id',$this->id)->get();
        $cr = $items->where('dc','C')->sum('amount');
        $dr = $items->where('dc','D')->sum('amount');
        return round($dr,2)-round($cr,2);
    }

    public function approvedBy()
    {
        return $this->belongsTo('\App\Models\Entry', 'approved_by');
    }
}