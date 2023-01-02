<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollAdvanceSalaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_advance_salary', function(Blueprint $table)
		{
			$table->integer('advance_salary_id', true);
			$table->integer('user_id');
			$table->string('advance_amount', 200);
			$table->string('deduct_month', 30)->nullable();
			$table->text('reason')->nullable();
			$table->timestamp('request_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->boolean('status')->default(0)->comment('0 =pending,1=accpect , 2 = reject and 3 = paid');
			$table->integer('approve_by')->nullable();
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
		Schema::drop('payroll_advance_salary');
	}

}
