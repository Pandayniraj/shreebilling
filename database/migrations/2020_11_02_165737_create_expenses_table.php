<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('org_id');
			$table->integer('project_id')->nullable();
			$table->integer('tag_id')->nullable();
			$table->integer('entry_id');
			$table->string('date');
			$table->string('expenses_account');
			$table->string('amount');
			$table->string('paid_through');
			$table->integer('vendor_id');
			$table->string('reference');
			$table->string('attachment', 2555);
			$table->enum('expense_type', array('expense','bill_payment','advance_payment','sales_return'));
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
		Schema::drop('expenses');
	}

}
