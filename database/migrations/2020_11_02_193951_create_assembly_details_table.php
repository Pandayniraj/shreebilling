<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssemblyDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assembly_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('assembly_id');
			$table->integer('product_id');
			$table->integer('units');
			$table->string('quantity');
			$table->string('wastage_qty')->nullable()->default('0');
			$table->string('cost_price');
			$table->string('total');
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
		Schema::drop('assembly_details');
	}

}
