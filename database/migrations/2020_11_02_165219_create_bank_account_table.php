<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_account', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id');
			$table->string('account_name');
			$table->string('account_code');
			$table->string('currency');
			$table->string('account_number');
			$table->string('bank_name');
			$table->integer('ledger_id');
			$table->string('routing_number');
			$table->text('description');
			$table->integer('created_by');
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
		Schema::drop('bank_account');
	}

}
