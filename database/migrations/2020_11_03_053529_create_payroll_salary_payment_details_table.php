<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryPaymentDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_payment_details', function(Blueprint $table)
		{
			$table->integer('salary_payment_details_id', true);
			$table->integer('salary_payment_id');
			$table->string('salary_payment_details_label', 200);
			$table->string('salary_payment_details_value', 200);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_salary_payment_details');
	}

}
