<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportPurchase extends Model
{
    use HasFactory;
    protected $table="importpurchase";
    protected $guarded=[];
}
