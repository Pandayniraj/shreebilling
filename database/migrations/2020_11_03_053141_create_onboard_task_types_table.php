<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardTaskTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('onboard_task_types', function(Blueprint $table)
		{
			$table->integer('id');
			$table->string('name');
			$table->integer('owner_id');
			$table->string('notified_before');
			$table->string('notify_email');
			$table->text('description');
			$table->integer('user_id');
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
		Schema::drop('onboard_task_types');
	}

}
