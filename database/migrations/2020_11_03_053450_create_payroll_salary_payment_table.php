<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_payment', function(Blueprint $table)
		{
			$table->integer('salary_payment_id', true);
			$table->integer('user_id');
			$table->integer('salary_template_id');
			$table->string('salary_grade', 50);
			$table->float('gratuity_salary', 10, 0);
			$table->string('payment_month', 20);
			$table->integer('gross_salary');
			$table->integer('total_allowance');
			$table->integer('total_deduction');
			$table->integer('fine_deduction');
			$table->string('payment_method', 20);
			$table->float('overtime', 10, 0);
			$table->integer('working_days');
			$table->text('comments')->nullable();
			$table->date('paid_date');
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
		Schema::drop('payroll_salary_payment');
	}

}
