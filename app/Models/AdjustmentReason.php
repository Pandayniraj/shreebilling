<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class AdjustmentReason extends Model
{
    /**
     * @var array
     */
    protected $table = 'adjustment_reasons';

    /**
     * @var array
     */
    protected $fillable = ['name', 'reason_type', 'trans_type'];


    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
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
        // Protect the admins and users Intakes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}