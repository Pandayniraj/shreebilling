<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_adjustment', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('date');
			$table->integer('location_id');
			$table->string('reason');
			$table->string('status')->nullable();
			$table->integer('ledgers_id');
			$table->string('vat_type')->nullable();
			$table->string('subtotal');
			$table->string('discount_percent')->default('0');
			$table->string('taxable_amount');
			$table->string('tax_amount')->nullable();
			$table->string('total_amount');
			$table->string('comments')->nullable();
			$table->string('entry_id')->nullable();
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
		Schema::drop('stock_adjustment');
	}

}
