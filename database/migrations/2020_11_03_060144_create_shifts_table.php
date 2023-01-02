<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shifts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('shift_name');
			$table->integer('color')->nullable();
			$table->string('shift_time')->nullable();
			$table->string('end_time')->nullable();
			$table->string('shift_margin_start')->nullable();
			$table->string('shift_margin_end')->nullable();
			$table->boolean('enabled');
			$table->boolean('is_night');
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
		Schema::drop('shifts');
	}

}
