<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryAllowanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_allowance', function(Blueprint $table)
		{
			$table->integer('salary_allowance_id', true);
			$table->integer('salary_template_id')->unsigned();
			$table->string('allowance_label', 200);
			$table->string('allowance_value', 200);
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
		Schema::drop('payroll_salary_allowance');
	}

}
