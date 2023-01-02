<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseEditHistory extends Model
{
    protected $table = 'expenses_edit_history';

    /**
     * @var array
     */
    protected $fillable = ['expense_id', 'from_amount', 'to_amount', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
