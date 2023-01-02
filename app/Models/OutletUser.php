<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletUser extends Model
{
      protected $table = 'outlet_users';
	
	/**
     * @var array
     */
    protected $fillable = ['outlet_id','user_id'];
	
        public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if ( \Auth::user()->id != $this->user_id && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3 ) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Leads from deletion
        /*if ( (\Auth::user()->id != $this->user_id  && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
            return false;
        } */
        
        if ( !\Auth::user()->hasRole('admins'))
            return false;

        return true;
    }
        
    public function user()
    {
        return $this->belongsTo('App\User');
    }

     public function outlet()
    {
        return $this->belongsTo('App\Models\PosOutlets');
    }
    
}
