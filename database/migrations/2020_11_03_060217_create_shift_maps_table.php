<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftMapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_maps', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('user_id');
			$table->integer('departments');
			$table->integer('shift_id');
			$table->string('map_from_date');
			$table->string('map_to_date');
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
		Schema::drop('shift_maps');
	}

}
