<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSalaryPayslipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_salary_payslip', function(Blueprint $table)
		{
			$table->integer('payslip_id', true);
			$table->string('payslip_number', 100)->nullable();
			$table->integer('salary_payment_id');
			$table->timestamp('payslip_generate_date')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_salary_payslip');
	}

}
