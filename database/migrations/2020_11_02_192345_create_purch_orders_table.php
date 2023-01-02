<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purch_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('org_id');
			$table->integer('supplier_id');
			$table->integer('project_id');
			$table->string('bill_no');
			$table->string('purchase_type')->nullable();
			$table->integer('user_id');
			$table->string('comments', 30);
			$table->date('ord_date');
			$table->string('due_date')->nullable();
			$table->date('bill_date');
			$table->date('delivery_date');
			$table->string('reference', 30);
			$table->string('status')->nullable();
			$table->string('vat_type')->nullable();
			$table->string('pan_no')->nullable();
			$table->string('into_stock_location', 50);
			$table->string('subtotal')->nullable();
			$table->string('discount_percent')->nullable();
			$table->string('taxable_amount')->nullable();
			$table->string('tax_amount')->nullable();
			$table->string('currency', 20)->nullable()->default('NPR');
			$table->float('total', 10, 0)->default(0);
			$table->string('payment_status')->nullable();
			$table->integer('ledger_id')->nullable();
			$table->string('fiscal_year');
			$table->integer('entry_id');
			$table->boolean('is_renewal')->default(0);
			$table->integer('fiscal_year_id');
			$table->string('discount_type')->nullable();
			$table->string('supplier_type')->nullable();
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
		Schema::drop('purch_orders');
	}

}
