<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationStockTransferTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_stock_transfer', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('status', array('parked','completed'))->default('parked');
			$table->string('transfer_date');
			$table->integer('source_id');
			$table->integer('destination_id');
			$table->integer('user_id');
			$table->string('quantity')->nullable();
			$table->string('comment')->nullable();
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
		Schema::drop('location_stock_transfer');
	}

}
