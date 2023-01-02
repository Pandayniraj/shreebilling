<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_details';

    protected $fillable = [
        'user_id', 'contract_start_date', 'contract_end_date', 'father_name', 'present_address', 'gender', 'marital_status', 'mother_name', 'bank_name', 'bank_account_name', 'bank_account_no', 'bank_account_no', 'bank_account_branch', 'education', 'skills', 'id_proof', 'resume', 'nationality', 'license_number', 'food', 'blood_group', 'emergency_contact_name', 'relationship', 'mobile', 'work_phone', 'amount', 'routing_num',
        'join_date', 'date_of_probation', 'date_of_permanent', 'last_promotion_date', 'last_transfer_date', 'date_of_retirement', 'working_status', 'employemnt_type', 'job_title','ethnicity','date_of_birth','permanent_address','citizenship_num','resgination_date'
    ];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if (\Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        if (! \Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }



    public function getExperience(){

        $service_tenure = strtotime($this->join_date);

        if($service_tenure){

            $datetime1 = new \DateTime($this->join_date);
            $datetime2 = new \DateTime(date('Y-m-d'));
            $interval = $datetime1->diff($datetime2);
            
            return $interval->format('%y years %m months');
        

        }else{

            
           return '';

        }


    }
}
