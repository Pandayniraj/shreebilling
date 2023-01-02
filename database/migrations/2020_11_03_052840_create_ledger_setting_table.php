<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerSettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ledger_setting', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id')->default(1);
			$table->string('ledger_name');
			$table->integer('ledger_id');
			$table->string('table_name');
			$table->integer('is_default');
			$table->text('description');
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
		Schema::drop('ledger_setting');
	}

}
