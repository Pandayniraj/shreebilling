<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_meta', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id');
			$table->boolean('sync_with_ird');
			$table->boolean('is_bill_active')->default(1);
			$table->string('void_reason')->nullable();
			$table->date('cancel_date')->nullable();
			$table->string('credit_note_no')->nullable();
			$table->integer('credit_user_id')->nullable();
			$table->boolean('is_realtime')->default(0);
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
		Schema::drop('invoice_meta');
	}

}
