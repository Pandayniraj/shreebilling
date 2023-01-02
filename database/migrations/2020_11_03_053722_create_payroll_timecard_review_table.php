<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTimecardReviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_timecard_review', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('pay_frequency_id');
			$table->float('regular_days', 10, 0);
			$table->float('ot_hour', 10, 0);
			$table->integer('weekend');
			$table->integer('public_holiday_work');
			$table->float('sick_leave', 10, 0)->nullable();
			$table->float('annual_leave', 10, 0)->nullable();
			$table->float('public_holidays', 10, 0)->nullable();
			$table->float('other_leave', 10, 0)->nullable();
			$table->string('time_entry_method');
			$table->string('remarks');
			$table->integer('issued_by');
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
		Schema::drop('payroll_timecard_review');
	}

}
