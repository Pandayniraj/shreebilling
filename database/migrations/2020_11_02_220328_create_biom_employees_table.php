<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiomEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('biom_employees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('machine_userid');
			$table->string('name');
			$table->integer('device_id')->unsigned()->index('biom_employees_device_id_foreign');
			$table->integer('group')->default(1);
			$table->integer('privilege')->default(0);
			$table->string('card')->default('0');
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
		Schema::drop('biom_employees');
	}

}
