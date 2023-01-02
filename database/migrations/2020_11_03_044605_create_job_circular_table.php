<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobCircularTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_circular', function(Blueprint $table)
		{
			$table->integer('job_circular_id', true);
			$table->string('job_title', 200);
			$table->integer('designation_id');
			$table->string('vacancy_no', 50);
			$table->date('posted_date');
			$table->enum('employment_type', array('contractual','full_time','part_time'))->default('full_time');
			$table->string('experience', 200)->nullable();
			$table->string('age', 200)->nullable();
			$table->string('salary_range', 200)->nullable();
			$table->date('last_date');
			$table->text('description');
			$table->enum('status', array('published','unpublished'))->default('unpublished')->comment('1=publish 2=unpublish');
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
		Schema::drop('job_circular');
	}

}
