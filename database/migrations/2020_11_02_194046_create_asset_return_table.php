<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetReturnTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_return', function(Blueprint $table)
		{
			$table->integer('return_item_id', true);
			$table->integer('project_id');
			$table->integer('stock_id');
			$table->integer('user_id');
			$table->integer('return_inventory');
			$table->date('return_date');
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
		Schema::drop('asset_return');
	}

}
