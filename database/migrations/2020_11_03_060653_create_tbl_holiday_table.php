<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblHolidayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_holiday', function(Blueprint $table)
		{
			$table->integer('holiday_id', true);
			$table->string('event_name', 200);
			$table->text('description');
			$table->date('start_date');
			$table->date('end_date');
			$table->string('location', 200)->nullable();
			$table->string('color', 200)->nullable();
			$table->string('types');
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
		Schema::drop('tbl_holiday');
	}

}
