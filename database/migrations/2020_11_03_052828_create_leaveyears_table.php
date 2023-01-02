<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveyearsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leaveyears', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id');
			$table->integer('user_id');
			$table->string('leave_year');
			$table->date('start_date');
			$table->date('end_date');
			$table->integer('current_year');
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
		Schema::drop('leaveyears');
	}

}
