<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityMaster extends Model
{
    protected $table = 'cities_master';
    protected $fillable=['city','lat','lng','country','iso2','iso3'];

    public function countries(){
          return $this->belongsTo(Country::class,'country');
    }
}
