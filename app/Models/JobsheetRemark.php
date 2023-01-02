<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsheetRemark extends Model
{
    use HasFactory;
    protected $fillable=['remark','faults','job_sheet_id'];
    protected $table='job_sheet_remarks';
}
