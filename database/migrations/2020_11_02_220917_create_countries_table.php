<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 3);
			$table->string('name', 150);
			$table->integer('dial_code');
			$table->string('currency_name', 20);
			$table->string('currency_symbol', 20);
			$table->string('currency_code', 20);
			$table->integer('display_order')->nullable();
			$table->boolean('enabled')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}
