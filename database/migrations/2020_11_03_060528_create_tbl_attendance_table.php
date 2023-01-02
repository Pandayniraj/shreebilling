<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_attendance', function(Blueprint $table)
		{
			$table->integer('attendance_id', true);
			$table->integer('org_id');
			$table->integer('user_id')->nullable();
			$table->integer('leave_category_id')->nullable()->default(0);
			$table->date('date_in')->nullable();
			$table->date('date_out')->nullable();
			$table->text('inLoc')->nullable();
			$table->text('outLoc')->nullable();
			$table->boolean('attendance_status')->default(0)->comment('status 0=absent 1=present 3 = onleave');
			$table->boolean('clocking_status');
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
		Schema::drop('tbl_attendance');
	}

}
