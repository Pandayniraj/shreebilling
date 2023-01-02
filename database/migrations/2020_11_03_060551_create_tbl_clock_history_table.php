<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblClockHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_clock_history', function(Blueprint $table)
		{
			$table->integer('clock_history_id', true);
			$table->integer('user_id')->nullable();
			$table->integer('clock_id');
			$table->time('clockin_edit');
			$table->time('clockout_edit')->nullable();
			$table->time('clockin_old');
			$table->time('clockout_old');
			$table->string('reason', 300);
			$table->boolean('status')->comment('1=pending and 2 = accept  3= reject');
			$table->boolean('notify_me');
			$table->boolean('view_status');
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
		Schema::drop('tbl_clock_history');
	}

}
