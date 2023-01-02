<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSheet extends Model
{
    use HasFactory;

    protected $table='job_sheets';
    protected $fillable=['device_status','customer_id','service_type','serial_number','brand', 'first_name', 'last_name', 'mobile', 'alt_mobile', 'email', 'address1', 'address2', 'product_type', 'model_name', 'model_number', 'device_config', 'passcode', 'IMEI_num1', 'serial_number', 'status', 'reported_fault', 'color', 'physical_state', 'estimated_cost', 'advance_paid', 'expected_delivery_date', 'expected_delivery_time', 'assigned_user','dropped_time','dropper_phone','dropper_email','dropper_name','collecter_phone','collected_by'];

    public function items(){
        return $this->hasMany(JobsheetItem::class,'job_sheet_id');
    }
    public function remarks(){
        return $this->hasMany(JobsheetRemark::class,'job_sheet_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'assigned_user');
    }
    public function customer(){
        return $this->belongsTo(Client::class,'customer_id');
    }
    public function type()
    {
        return $this->belongsTo(\App\Models\ProductCategory::class,'product_type');
    }

}

