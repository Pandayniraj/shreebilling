<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_adjustment_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('adjustment_id');
			$table->integer('product_id');
			$table->string('price');
			$table->string('qty');
			$table->string('unit');
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
		Schema::drop('stock_adjustment_details');
	}

}
