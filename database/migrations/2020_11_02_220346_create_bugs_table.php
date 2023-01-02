<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bugs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id')->nullable();
			$table->integer('project_id');
			$table->integer('user_id');
			$table->integer('assigned_to');
			$table->string('category', 55);
			$table->text('description');
			$table->string('fixed_in_release', 55);
			$table->string('found_in_release', 55);
			$table->enum('priority', array('l','h','m'));
			$table->text('resolution');
			$table->enum('source', array('internal','email','cases','client'));
			$table->enum('status', array('open','in_progress','assigned','to_be_fixed','reopen','fixed'));
			$table->string('subject');
			$table->enum('type', array('defect','feature'));
			$table->boolean('viewed')->default(0);
			$table->boolean('enabled')->default(0);
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
		Schema::drop('bugs');
	}

}
