<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id')->nullable();
			$table->integer('customer_group');
			$table->string('name');
			$table->string('location');
			$table->string('phone', 50);
			$table->string('vat');
			$table->string('email');
			$table->string('website');
			$table->string('stock_symbol');
			$table->boolean('enabled')->default(1);
			$table->timestamps(10);
			$table->enum('industry', array('Hospitality','Travel','Education','Health','IT','ISP','Banking','Brokers','Trading','Construction','Government','Engineering','Media','Finance','Retail'));
			$table->string('type');
			$table->text('notes');
			$table->text('reminder');
			$table->string('bank_name');
			$table->string('bank_branch');
			$table->string('bank_account');
			$table->enum('relation_type', array('customer','supplier'));
			$table->text('physical_address');
			$table->integer('ledger_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clients');
	}

}
