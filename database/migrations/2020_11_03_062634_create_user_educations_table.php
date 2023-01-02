<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_educations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_detail_id');
			$table->string('level');
			$table->string('institute');
			$table->string('major');
			$table->string('year')->nullable();
			$table->string('score')->nullable();
			$table->string('start_date')->nullable();
			$table->string('end_date')->nullable();
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
		Schema::drop('user_educations');
	}

}
