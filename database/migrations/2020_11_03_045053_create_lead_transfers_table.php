<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadTransfersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_transfers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('from_user_id');
			$table->integer('to_user_id');
			$table->integer('lead_id');
			$table->integer('notify')->nullable()->default(0);
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
		Schema::drop('lead_transfers');
	}

}
