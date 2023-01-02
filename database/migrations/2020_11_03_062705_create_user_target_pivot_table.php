<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTargetPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_target_pivot', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_target_id')->unsigned();
			$table->integer('course_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('target');
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
		Schema::drop('user_target_pivot');
	}

}
