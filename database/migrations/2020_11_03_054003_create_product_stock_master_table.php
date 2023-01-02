<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_stock_master', function(Blueprint $table)
		{
			$table->integer('stock_id', true);
			$table->boolean('product_category_id');
			$table->string('product_name');
			$table->string('units', 30);
			$table->boolean('inactive')->default(0);
			$table->boolean('deleted_status')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_stock_master');
	}

}
