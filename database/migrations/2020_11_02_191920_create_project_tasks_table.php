<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('project_id')->index('project_tasks_project_id_foreign');
			$table->integer('user_id');
			$table->string('subject');
			$table->date('timespan')->nullable();
			$table->string('schedule')->nullable();
			$table->text('description');
			$table->string('percent_complete');
			$table->string('actual_duration');
			$table->string('duration');
			$table->string('estimated_effort');
			$table->boolean('milestone')->default(0);
			$table->string('order');
			$table->string('precede_tasks');
			$table->string('priority');
			$table->string('peoples');
			$table->integer('task_order');
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->string('attachment')->nullable();
			$table->string('status')->default('new');
			$table->boolean('enabled')->default(0);
			$table->timestamps(10);
			$table->integer('org_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->integer('stage_id');
			$table->string('color');
			$table->boolean('synced_with_google')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('project_tasks');
	}

}
