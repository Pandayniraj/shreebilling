<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadActivityStreamTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_activity_stream', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('lead_id');
			$table->string('column_name')->nullable();
			$table->string('related_status_or_rating_id')->nullable();
			$table->string('change_type');
			$table->string('icons', 50);
			$table->date('date');
			$table->string('activity')->nullable();
			$table->integer('user_id');
			$table->integer('task_assigned_to');
			$table->string('color', 100);
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
		Schema::drop('lead_activity_stream');
	}

}
