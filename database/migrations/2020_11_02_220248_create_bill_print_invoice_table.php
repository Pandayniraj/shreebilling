<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPrintInvoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bill_print_invoice', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id');
			$table->timestamp('printed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('printed_by');
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
		Schema::drop('bill_print_invoice');
	}

}
