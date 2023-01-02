<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheet', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->string('date');
			$table->integer('org_id');
			$table->string('time_from');
			$table->string('time_to');
			$table->string('comments');
			$table->integer('activity_id');
			$table->string('date_submitted');
			$table->enum('status', array('s','a','p'))->comment('s= submitted, p= pending, a=approved');
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
		Schema::drop('timesheet');
	}

}
