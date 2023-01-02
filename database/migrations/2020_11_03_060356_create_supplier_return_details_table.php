<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierReturnDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_return_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('supplier_return_id');
			$table->integer('product_id')->nullable();
			$table->string('description')->nullable();
			$table->integer('units')->nullable();
			$table->string('purchase_quantity');
			$table->string('return_quantity');
			$table->string('purchase_price');
			$table->string('return_price');
			$table->string('return_total');
			$table->string('reason');
			$table->integer('is_inventory');
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
		Schema::drop('supplier_return_details');
	}

}
