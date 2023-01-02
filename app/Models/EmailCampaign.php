<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $table = 'email_campaign';

    /**
     * @var array
     */
    protected $fillable = ['title', 'subject', 'message', 'condition_detail', 'db_query', 'total_email', 'campaign_start_date', 'total_email_sent', 'last_lead_id', 'last_sent_date'];

    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1) {
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
        if (\Auth::user()->id != $this->user_id && \Auth::user()->id != 1) {
            return false;
        }

        return true;
    }
}
