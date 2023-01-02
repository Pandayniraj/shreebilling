<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaigns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('list_id');
			$table->string('name', 200);
			$table->date('start_date');
			$table->date('end_date');
			$table->string('currency', 200);
			$table->string('budget', 200);
			$table->string('expected_cost', 200);
			$table->string('actual_cost', 200);
			$table->string('expected_revenue', 200);
			$table->string('campaign_type', 200);
			$table->text('objective');
			$table->text('content');
			$table->boolean('enabled');
			$table->string('camp_status', 200);
			$table->integer('user_id');
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
		Schema::drop('campaigns');
	}

}
