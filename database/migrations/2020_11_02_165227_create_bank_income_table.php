<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankIncomeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_income', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('customer_id');
			$table->enum('income_type', array('customer_payment','customer_advance','sales_without_invoice','other_income','interest_income'));
			$table->float('amount', 10, 0)->nullable();
			$table->date('date_received')->nullable();
			$table->string('reference_no');
			$table->enum('received_via', array('cash','check','cc','transfer','remittance'));
			$table->text('description');
			$table->integer('income_account');
			$table->integer('user_id');
			$table->integer('entry_id');
			$table->integer('tag_id')->nullable();
			$table->integer('fiscal_year_id');
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
		Schema::drop('bank_income');
	}

}
