<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_payment', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('invoice_id')->nullable();
			$table->integer('return_id')->nullable();
			$table->string('reference_no', 50);
			$table->string('transaction_id', 50)->nullable();
			$table->string('paid_by', 20);
			$table->string('cheque_no', 20)->nullable();
			$table->string('cc_no', 20)->nullable();
			$table->string('cc_holder', 25)->nullable();
			$table->string('cc_month', 2)->nullable();
			$table->string('cc_year', 4)->nullable();
			$table->string('cc_type', 20)->nullable();
			$table->decimal('amount', 25, 4);
			$table->string('currency', 3)->nullable();
			$table->integer('created_by');
			$table->string('attachment')->nullable();
			$table->string('type', 20);
			$table->string('note', 1000)->nullable();
			$table->decimal('pos_paid', 25, 4)->nullable();
			$table->decimal('pos_balance', 25, 4)->nullable();
			$table->string('approval_code', 50)->nullable();
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
		Schema::drop('invoice_payment');
	}

}
