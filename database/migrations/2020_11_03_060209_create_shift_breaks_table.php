<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftBreaksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_breaks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('shift_id');
			$table->string('name');
			$table->string('icon', 200);
			$table->time('start_time');
			$table->time('end_time');
			$table->enum('pay_type', array('paid','unpaid'));
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
		Schema::drop('shift_breaks');
	}

}
