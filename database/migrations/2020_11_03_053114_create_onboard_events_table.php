<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('onboard_events', function(Blueprint $table)
		{
			$table->integer('id');
			$table->string('name');
			$table->string('location');
			$table->date('due_date');
			$table->string('participants', 300);
			$table->string('owner', 300);
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
		Schema::drop('onboard_events');
	}

}
