<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsheetItem extends Model
{
    protected $fillable=['item','job_sheet_id'];
    protected $table='job_sheet_items';
    use HasFactory;
}
