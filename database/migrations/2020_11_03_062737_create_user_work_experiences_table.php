<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkExperiencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_work_experiences', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_detail_id');
			$table->string('company')->nullable();
			$table->string('job_title')->nullable();
			$table->string('date_from')->nullable();
			$table->string('date_to')->nullable();
			$table->string('comment')->nullable();
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
		Schema::drop('user_work_experiences');
	}

}
