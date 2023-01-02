<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fin_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bill_no');
			$table->integer('user_id')->unsigned()->nullable()->index('orders_user_id_foreign');
			$table->string('source', 200);
			$table->integer('client_id')->unsigned()->default(1)->index('orders_seller_id_foreign');
			$table->integer('org_id')->nullable();
			$table->integer('entry_id')->nullable();
			$table->enum('order_type', array('quotation','proforma_invoice','order'));
			$table->string('name');
			$table->string('position', 250);
			$table->string('address');
			$table->string('from_stock_location', 250)->nullable();
			$table->string('payment_status');
			$table->text('comment');
			$table->dateTime('ship_date')->nullable();
			$table->dateTime('require_date')->nullable();
			$table->integer('sales_tax')->nullable();
			$table->string('status', 100)->nullable();
			$table->string('paid', 98)->nullable();
			$table->dateTime('bill_date')->nullable();
			$table->date('due_date')->nullable();
			$table->timestamps(10);
			$table->decimal('taxable_amount', 15)->nullable();
			$table->float('tax_amount', 10, 0);
			$table->string('discount_amount', 20)->nullable();
			$table->text('discount_note')->nullable();
			$table->decimal('total_amount', 15)->nullable();
			$table->float('paid_amount', 10, 0);
			$table->decimal('subtotal', 15);
			$table->string('trans_type');
			$table->string('fiscal_year');
			$table->string('customer_pan');
			$table->float('amount', 10, 0);
			$table->boolean('sync_with_ird');
			$table->boolean('is_bill_printed');
			$table->boolean('is_bill_active');
			$table->dateTime('printed_time');
			$table->integer('printed_by');
			$table->boolean('is_realtime');
			$table->string('terms')->nullable();
			$table->string('discount_percent', 200)->nullable();
			$table->boolean('is_renewal')->default(0);
			$table->integer('fiscal_year_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fin_orders');
	}

}
