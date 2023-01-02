<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purch_order_details', function(Blueprint $table)
		{
			$table->increments('po_detail_item');
			$table->integer('order_no')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->string('description');
			$table->float('qty_invoiced', 10, 0)->default(0);
			$table->float('unit_price', 10, 0)->default(0);
			$table->integer('tax_type_id')->nullable();
			$table->float('quantity_ordered', 10, 0)->default(0);
			$table->float('quantity_recieved', 10, 0)->default(0);
			$table->integer('suplier_id');
			$table->float('total', 10, 0);
			$table->integer('is_inventory');
			$table->string('units');
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
		Schema::drop('purch_order_details');
	}

}
