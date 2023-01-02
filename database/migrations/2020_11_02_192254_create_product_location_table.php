<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_location', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('org_id');
			$table->string('loc_code', 10);
			$table->string('location_name', 60);
			$table->string('delivery_address', 60);
			$table->string('email', 60);
			$table->string('phone', 60);
			$table->string('contact_person', 60);
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
		Schema::drop('product_location');
	}

}
