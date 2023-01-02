<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblClockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_clock', function(Blueprint $table)
		{
			$table->integer('clock_id', true);
			$table->integer('attendance_id');
			$table->time('clockin_time')->nullable();
			$table->time('clockout_time')->nullable();
			$table->text('comments')->nullable();
			$table->string('in_device');
			$table->string('out_device');
			$table->boolean('clocking_status')->default(0)->comment('1= clockin_time, 2=break_out, 3=break_in, 4=clockout_time');
			$table->boolean('time_changed')->nullable()->default(0);
			$table->string('ip_address', 50)->nullable();
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
		Schema::drop('tbl_clock');
	}

}
