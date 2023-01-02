<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiomDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('biom_devices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('device_name');
			$table->integer('serial_number')->default(1);
			$table->string('ip_address')->default('169.254.0.1');
			$table->boolean('isActive');
			$table->integer('com_key')->default(0);
			$table->string('description');
			$table->integer('soap_port')->default(80);
			$table->integer('udp_port')->default(4370);
			$table->string('encoding')->default('utf-8');
			$table->integer('connection_timeout')->default(2);
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
		Schema::drop('biom_devices');
	}

}
