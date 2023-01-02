<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockMovesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_stock_moves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('stock_id', 30);
			$table->integer('order_no');
			$table->smallInteger('trans_type')->default(0);
			$table->date('tran_date');
			$table->integer('user_id')->nullable();
			$table->string('order_reference', 30);
			$table->string('reference', 30);
			$table->integer('transaction_reference_id');
			$table->string('note');
			$table->float('qty', 10, 0)->default(0);
			$table->integer('location');
			$table->float('price', 10, 0)->default(0);
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
		Schema::drop('product_stock_moves');
	}

}
