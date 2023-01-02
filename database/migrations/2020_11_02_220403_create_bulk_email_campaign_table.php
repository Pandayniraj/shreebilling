<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkEmailCampaignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bulk_email_campaign', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('subject');
			$table->text('message');
			$table->integer('product_id');
			$table->integer('status_id');
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
		Schema::drop('bulk_email_campaign');
	}

}
