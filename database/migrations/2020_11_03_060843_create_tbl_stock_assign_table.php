<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStockAssignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_stock_assign', function(Blueprint $table)
		{
			$table->integer('assign_item_id', true);
			$table->integer('project_id');
			$table->integer('stock_id');
			$table->integer('user_id');
			$table->integer('assign_inventory');
			$table->date('assign_date');
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
		Schema::drop('tbl_stock_assign');
	}

}
