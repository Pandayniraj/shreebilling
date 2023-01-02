<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSpaceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_space', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('event_id');
			$table->string('room_name');
			$table->string('room_capability');
			$table->float('daily_rate', 6);
			$table->dateTime('occupied_date_from');
			$table->dateTime('occupied_date_to');
			$table->enum('booking_status', array('confirmed','provisional'));
			$table->integer('other_details');
			$table->integer('user_id');
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
		Schema::drop('event_space');
	}

}
