<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class RemoteTaxDeductionCategory extends Model
{
    /**
     * @var array
     */
    protected $table = 'remote_tax_deduction_categories';

    /**
     * @var array
     */

    protected $fillable = ['group_name','tax_amount'];


       /**
     * @return bool
     */
    // public function isDeletable()
    // {
    //     // Protect the admins and users Communication from deletion
    //     if (('admins' == $this->name)) {
    //         return false;
    //     }

    //     return true;
    // }


}
