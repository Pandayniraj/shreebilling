<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiomLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('biom_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('machine_attendence_id');
			$table->integer('machine_userid');
			$table->dateTime('datetime');
			$table->integer('verification_mode');
			$table->integer('verified')->default(0);
			$table->integer('device_id')->unsigned()->index('device_id');
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
		Schema::drop('biom_logs');
	}

}
