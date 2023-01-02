<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSendError extends Model
{
    protected $table = 'email_send_error';

    /**
     * @var array
     */
    protected $fillable = ['email', 'subject'];

    public function campaign()
    {
        return $this->belongsTo(\App\Models\EmailCampaign::class, 'email_campaign_id');
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class, 'lead_id');
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'lead_id');
    }
}
