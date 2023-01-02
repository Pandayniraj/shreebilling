<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAttachment extends Model
{
    protected $table = 'cases_attachment';

    /**
     * @var array
     */
    protected $fillable = ['case_id', 'attachment', 'user_id'];
}
