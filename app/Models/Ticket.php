<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = ['ticket_number', 'user_id', 'from_user', 'from_email', 'from_ext', 'from_phone', 'cc_users', 'notice', 'model_no', 'serial_no', 'customer', 'source', 'help_topic', 'department_id', 'sla_plan', 'due_date', 'assign_to', 'issue_summary', 'detail_reason', 'ticket_status', 'internal_notes', 'form_source','customer_id'];

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

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function customerName()
    {
        return $this->belongsTo(\App\Models\Client::class,'customer_id');
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Communication from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}
