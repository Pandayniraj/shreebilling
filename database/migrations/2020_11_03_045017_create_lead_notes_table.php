<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_notes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('note');
			$table->integer('lead_id')->unsigned()->default(0);
			$table->integer('user_id')->unsigned()->default(1);
			$table->boolean('enabled')->default(1);
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
		Schema::drop('lead_notes');
	}

}
