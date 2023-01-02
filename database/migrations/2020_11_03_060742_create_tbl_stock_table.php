<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_stock', function(Blueprint $table)
		{
			$table->integer('stock_id', true);
			$table->integer('project_id');
			$table->integer('stock_sub_category_id');
			$table->string('item_name', 200);
			$table->integer('total_stock')->nullable();
			$table->string('unit_price', 250);
			$table->date('buying_date');
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
		Schema::drop('tbl_stock');
	}

}
