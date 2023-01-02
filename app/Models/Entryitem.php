<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entryitem extends Model
{
    /**
     * @var array
     */

    protected $table = 'entryitems';
    /**
     * @var array
     */
    protected $fillable = ['entry_id','org_id','ledger_id','amount','dc','reconciliation_date','narration','cheque_no','currency_id','is_additional_cost'];


    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leadtypes from editing changes
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
        // Protect the admins and users Leadtypes from deletion
        if ( ('admins' == $this->name) || ('users' == $this->name) ) {
            return false;
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ledgerdetail()
    {
        return $this->belongsTo('App\Models\COALedgers','ledger_id');
    }

    public function lead()
    {
        return $this->hasOne('App\Models\Lead');
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class,'entry_id','id');


    }
    public function dynamicEntry()
    {
        $entry_item_table=new Entry();
        $prefix='';

        if (\Request::get('fiscal_year')){
            $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
            $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
            if ($fiscal_year!=$current_fiscal->fiscal_year){
                $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
//                $entry_item_table=new Entry();
                $new_item_table=$prefix.'entries';
                $entry_item_table->setTable($new_item_table);
            }
        }
        return $entry_item_table->find($this->entry_id);
    }

    public function entrytype()
    {
        return $this->belongsTo('App\Models\Entrytype','entry_id','entrytype_id');
    }


    public function tagname()
    {
        return $this->belongsTo('App\Models\Tag','entry_id','tag_id');
    }

    public function parent()
    {
        return $this->belongsTo('\App\Models\COAgroups', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('\App\Models\COAgroups', 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
    public function itemDetail()
    {
        return $this->hasMany(EntryItemDetail::class,'entryitem_id');
    }

}
