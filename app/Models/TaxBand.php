<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class TaxBand extends Model
{
    /**
     * @var array
     */
    protected $table = 'tax_band';

    /**
     * @var array
     */
    protected $fillable = ['band_name', 'from_amount', 'to_amount','tax_percentage', 'fiscal_year','marital_status'];


   

}

