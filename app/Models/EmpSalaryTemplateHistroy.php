<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpSalaryTemplateHistroy extends Model
{
    protected $table = 'payroll_salary_template_histroy';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'created_by', 'salary_template_id'];

    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\SalaryTemplate::class, 'salary_template_id', 'salary_template_id');
    }
}
