<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTaskStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_task_status', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('project_id');
			$table->string('name');
			$table->string('description');
			$table->boolean('enabled');
			$table->timestamps(10);
			$table->unique(['name','project_id'], 'name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('project_task_status');
	}

}
