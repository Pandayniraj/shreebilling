<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * @var array
     */
    //reason id => is lead_closure id
    protected $fillable = ['lead_type_id', 'title', 'name', 'dob', 'price_value', 'description', 'mob_phone', 'home_phone', 'address_line_1', 'address_line_2', 'city', 'country', 'email', 'email_opt_out', 'awarding_body', 'homepage', 'product_id', 'communication_id', 'status_id', 'admission_process_id', 'rating', 'user_id', 'gender', 'nationality', 'marital_status', 'disability', 'submit_from', 'online_application_status', 'language_of_instruction', 'peroid_attended', 'job_title', 'job_start', 'job_end', 'job_desc', 'enabled', 'sector', 'logo', 'target_date', 'skype', 'org_id', 'custom_product', 'dob', 'stage_id', 'company_id', 'campaign_id', 'ledger_id', 'moved_to_client', 'reason_id','mob_phone2','contact_id'];

    /**
     * @return bool
     */
  public function isEditable()
    {
        // Protect the admins and users Communication from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Leads from deletion
        /*if ( (\Auth::user()->id != $this->user_id  && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
            return false;
        } */

        if (! \Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isEnableDisable()
    {
        // Protect the admins and users Leads from enabling disabling
        if ((\Auth::user()->id != $this->user_id && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isenabled()
    {
        // Protect the admins and users Leads from deletion
        if ((\Auth::user()->id != $this->user_id && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canChangePermissions()
    {
        // Protect the admins Lead from permissions changes
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 && \Auth::user()->id != 3) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canChangeMembership()
    {
        // Protect the users Lead from membership changes
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1) {
            return false;
        }

        return true;
    }

    /**
     * @param $Lead
     * @return bool
     */
    public function isForced($Lead)
    {
        if (\Auth::user()->id != $this->user_id) {
            return true;
        }

        return false;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Lead has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }

    /**
     * Force the Lead to have the given permission.
     *
     * @param $permissionName
     */
    public function forcePermission($permissionName)
    {
        // If the Lead has not been given a the said permission
        if (null == $this->perms()->where('name', $permissionName)->first()) {
            // Load the given permission and attach it to the Lead.
            $permToForce = Permission::where('name', $permissionName)->first();
            $this->perms()->attach($permToForce->id);
        }
    }

    /**
     * Save the inputted users.
     *
     * @param mixed $inputUsers
     *
     * @return void
     */
    public function saveUsers($inputUsers)
    {
        if (! empty($inputUsers)) {
            $this->users()->sync($inputUsers);
        } else {
            $this->users()->detach();
        }
    }

    public static function query()
    {
        return ['id'=>1, 'name'=>'abc'];
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function communication()
    {
        return $this->belongsTo(\App\Models\Communication::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Leadstatus::class);
    }

    public function stage()
    {
        return $this->belongsTo(\App\Models\Stage::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function leadType()
    {
        return $this->belongsTo(\App\Models\Leadtype::class);
    }

    public function admission_process()
    {
        return $this->belongsTo(\App\Models\Admissionprocess::class);
    }

    public function campaign()
    {
        return $this->belongsTo(\App\Models\Campaigns::class, 'campaign_id');
    }

    public function reason()
    {
        return $this->belongsTo(\App\Models\LeadClosureReason::class, 'reason_id');
    }

    public function ratings()
    {
        return $this->belongsTo(\App\Models\Rating::class, 'rating');
    }
}
