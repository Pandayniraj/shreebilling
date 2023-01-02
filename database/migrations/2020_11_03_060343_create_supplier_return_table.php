<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierReturnTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_return', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('purchase_bill_no');
			$table->integer('supplier_id');
			$table->integer('entry_id')->nullable();
			$table->string('return_date')->nullable();
			$table->string('purchase_order_date')->nullable();
			$table->integer('user_id');
			$table->integer('org_id');
			$table->string('pan_no')->nullable();
			$table->string('vat_type')->nullable();
			$table->string('status')->nullable();
			$table->string('is_renewal');
			$table->integer('into_stock_location')->nullable();
			$table->string('subtotal');
			$table->string('discount_percent')->nullable();
			$table->string('taxable_amount');
			$table->string('tax_amount')->nullable();
			$table->string('total_amount');
			$table->string('comments')->nullable();
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
		Schema::drop('supplier_return');
	}

}
