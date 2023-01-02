<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeEditHistory extends Model
{
    protected $table = 'income_edit_history';

    /**
     * @var array
     */
    protected $fillable = ['income_id', 'from_amount', 'to_amount', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
