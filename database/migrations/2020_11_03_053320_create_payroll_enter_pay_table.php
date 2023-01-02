<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollEnterPayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_enter_pay', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('pay_frequency_id');
			$table->float('basic_salary', 10, 0);
			$table->float('regular_salary', 10, 0);
			$table->float('gratuity_salary', 10, 0)->nullable();
			$table->float('overtime_salary', 10, 0);
			$table->float('weekend_salary', 10, 0)->nullable();
			$table->float('public_holiday_work_salary', 10, 0)->nullable();
			$table->float('sick_salary', 10, 0)->nullable();
			$table->float('annual_leave_salary', 10, 0)->nullable();
			$table->float('public_holiday_salary', 10, 0)->nullable();
			$table->float('other_leave_salary', 10, 0)->nullable();
			$table->float('net_salary', 10, 0);
			$table->float('tax_amount', 10, 0);
			$table->float('tax_percent', 10, 0);
			$table->float('gross_salary', 10, 0);
			$table->integer('issued_by');
			$table->string('remarks');
			$table->float('total_allowance', 10, 0);
			$table->text('total_allowance_json')->nullable();
			$table->float('total_deduction', 10, 0);
			$table->text('total_deduction_json')->nullable();
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
		Schema::drop('payroll_enter_pay');
	}

}
