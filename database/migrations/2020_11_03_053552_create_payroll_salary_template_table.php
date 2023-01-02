<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_template', function(Blueprint $table)
		{
			$table->integer('salary_template_id', true);
			$table->string('salary_grade', 200);
			$table->float('annual_leave_salary', 10, 0);
			$table->string('basic_salary', 200);
			$table->float('gratuity_salary', 10, 0);
			$table->string('overtime_salary', 100);
			$table->float('sick_salary', 10, 0);
			$table->float('annual_salary', 10, 0);
			$table->float('public_holiday_salary', 10, 0);
			$table->float('other_leave_salary', 10, 0);
			$table->float('salary_per_day', 10, 0);
			$table->timestamps(10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_salary_template');
	}

}
