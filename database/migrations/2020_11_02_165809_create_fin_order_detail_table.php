<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinOrderDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fin_order_detail', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('client_id')->unsigned();
			$table->integer('order_id')->unsigned()->index('order_id');
			$table->integer('product_id')->unsigned()->nullable();
			$table->integer('unit')->nullable();
			$table->decimal('price', 11)->nullable();
			$table->integer('quantity')->nullable();
			$table->string('tax_amount', 100)->nullable();
			$table->string('tax', 100)->nullable();
			$table->decimal('total', 13)->nullable();
			$table->dateTime('bill_date')->nullable();
			$table->dateTime('date')->nullable();
			$table->timestamps(10);
			$table->string('description', 200)->nullable();
			$table->string('is_inventory', 200)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fin_order_detail');
	}

}
