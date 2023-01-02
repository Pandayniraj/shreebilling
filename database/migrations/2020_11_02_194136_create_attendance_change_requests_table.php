<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceChangeRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attendance_change_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('attendance_id');
			$table->integer('user_id');
			$table->dateTime('actual_time');
			$table->dateTime('requested_time');
			$table->integer('status')->default(1)->comment('1=pending,2=approved,3=rejected');
			$table->integer('approved_by');
			$table->text('reason');
			$table->integer('created_at');
			$table->integer('updated_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('attendance_change_requests');
	}

}
