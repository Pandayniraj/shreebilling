<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStickyNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sticky_note', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('title')->nullable();
			$table->text('description')->nullable();
			$table->string('color')->nullable();
			$table->timestamps(10);
			$table->integer('user_id');
			$table->integer('org_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sticky_note');
	}

}
