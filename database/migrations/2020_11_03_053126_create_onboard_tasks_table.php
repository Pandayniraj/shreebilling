<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('onboard_tasks', function(Blueprint $table)
		{
			$table->integer('id');
			$table->integer('event_id');
			$table->string('participants', 500);
			$table->integer('task_owner');
			$table->string('name');
			$table->text('description');
			$table->string('notify_mail');
			$table->date('due_date');
			$table->date('effective_date');
			$table->string('priority');
			$table->integer('user_id');
			$table->integer('notified_before');
			$table->integer('created_at');
			$table->integer('updated_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('onboard_tasks');
	}

}
