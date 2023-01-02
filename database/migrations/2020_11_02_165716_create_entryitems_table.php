<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryitemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entryitems', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('org_id')->nullable();
			$table->bigInteger('entry_id')->index('entry_id');
			$table->integer('user_id')->nullable();
			$table->bigInteger('ledger_id')->index('ledger_id');
			$table->decimal('amount', 25)->default(0.00);
			$table->char('dc', 1);
			$table->date('reconciliation_date')->nullable();
			$table->string('narration', 500);
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
		Schema::drop('entryitems');
	}

}
