<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryDeductionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_deduction', function(Blueprint $table)
		{
			$table->integer('salary_deduction_id', true);
			$table->integer('salary_template_id');
			$table->string('deduction_label', 200);
			$table->string('deduction_value', 200);
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
		Schema::drop('payroll_salary_deduction');
	}

}
