<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeolocationHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geolocation_history', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('latitude');
			$table->string('longitude');
			$table->string('ip_address');
			$table->string('street_name');
			$table->string('formatted_address');
			$table->date('tracked_date');
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
		Schema::drop('geolocation_history');
	}

}
