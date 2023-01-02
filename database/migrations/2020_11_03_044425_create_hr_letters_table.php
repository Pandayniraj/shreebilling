<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hr_letters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('staff_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('org_id')->nullable();
			$table->enum('type', array('contract','promotion','intern','warning','others'));
			$table->string('subject');
			$table->text('body');
			$table->string('template', 30);
			$table->enum('status', array('draft','paused','finalized','issued'));
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
		Schema::drop('hr_letters');
	}

}
