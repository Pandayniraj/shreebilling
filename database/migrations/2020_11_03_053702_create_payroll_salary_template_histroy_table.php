<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryTemplateHistroyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_template_histroy', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('created_by');
			$table->integer('user_id');
			$table->integer('salary_template_id');
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
		Schema::drop('payroll_salary_template_histroy');
	}

}
