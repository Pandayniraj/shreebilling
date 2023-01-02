<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('lead_id')->unsigned();
			$table->integer('contact_id');
			$table->integer('org_id')->nullable();
			$table->enum('task_type', array('meeting','outbound','appointment','vacation','todo','inbound','other'));
			$table->string('location');
			$table->string('duration');
			$table->string('task_subject');
			$table->text('task_detail');
			$table->string('task_status');
			$table->integer('task_owner')->unsigned();
			$table->string('task_assign_to');
			$table->string('task_priority');
			$table->timestamp('task_start_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('task_due_date');
			$table->integer('task_complete_percent');
			$table->boolean('task_alert');
			$table->string('color', 100);
			$table->boolean('enabled');
			$table->boolean('viewed')->default(0);
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
		Schema::drop('tasks');
	}

}
