<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cities_master', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('city');
			$table->string('lat', 200);
			$table->string('lng', 200);
			$table->string('country', 200);
			$table->string('iso2', 50);
			$table->string('iso3', 50);
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
		Schema::drop('cities_master');
	}

}
