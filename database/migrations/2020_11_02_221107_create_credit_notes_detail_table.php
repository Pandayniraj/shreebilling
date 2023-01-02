<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNotesDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('credit_notes_detail', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('client_id')->unsigned();
			$table->integer('credit_note_id')->unsigned();
			$table->integer('product_id')->unsigned()->nullable();
			$table->string('description')->nullable();
			$table->decimal('price', 11)->nullable();
			$table->integer('quantity')->nullable();
			$table->string('tax', 10)->nullable();
			$table->float('tax_amount', 10, 0)->nullable();
			$table->decimal('total', 13)->nullable();
			$table->dateTime('bill_date')->nullable();
			$table->dateTime('date')->nullable();
			$table->timestamps(10);
			$table->string('is_inventory')->nullable();
			$table->string('returnable')->nullable();
			$table->string('credit_price')->nullable();
			$table->string('credit_qty')->nullable();
			$table->string('reason')->nullable();
			$table->string('credit_total')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('credit_notes_detail');
	}

}
