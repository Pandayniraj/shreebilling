<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasePart1 extends Model
{
    protected $table = 'case_part1';

    /**
     * @var array
     */
    protected $fillable = ['part_name', 'part_code', 'description', 'quantity', 'rate', 'amount', 'remark', 'case_id'];
}
