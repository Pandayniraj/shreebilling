<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationStockTransferDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_stock_transfer_detail', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('location_stock_transfer_id');
			$table->integer('product_id');
			$table->integer('quantity');
			$table->string('reason')->nullable();
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
		Schema::drop('location_stock_transfer_detail');
	}

}
