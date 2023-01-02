<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasePart2 extends Model
{
    protected $table = 'case_part2';

    /**
     * @var array
     */
    protected $fillable = ['visit_date_time', 'service_engineer', 'call_status', 'peding_reasons', 'remarks', 'case_id'];
}
