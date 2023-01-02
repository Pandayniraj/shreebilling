<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assets', function(Blueprint $table)
		{
			$table->integer('stock_id', true);
			$table->integer('project_id');
			$table->integer('stock_sub_category_id');
			$table->string('item_name', 200);
			$table->integer('total_stock')->nullable();
			$table->string('unit_price', 250);
			$table->date('buying_date');
			$table->integer('departments_id');
			$table->string('types');
			$table->string('conditions');
			$table->string('item_model');
			$table->string('location');
			$table->integer('supplier');
			$table->integer('invoice_number');
			$table->float('unit_salvage_value', 10, 0);
			$table->date('service_date');
			$table->float('depreciation_rate', 10, 0);
			$table->float('annual_depreciation', 10, 0);
			$table->float('accumulated_depreciation', 10, 0);
			$table->integer('asset_number');
			$table->integer('fiscal_year_id')->nullable();
			$table->enum('asset_status', array('draft','registered','sold','disposed'))->nullable();
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
		Schema::drop('assets');
	}

}
