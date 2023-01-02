<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailCampaignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_campaign', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('subject');
			$table->text('message');
			$table->string('attachement');
			$table->text('condition_detail');
			$table->text('db_query');
			$table->string('campaign_type', 200);
			$table->integer('total_email');
			$table->date('campaign_start_date');
			$table->integer('total_email_sent');
			$table->integer('today_email_sent_count');
			$table->integer('total_email_error_count');
			$table->integer('last_lead_id');
			$table->date('last_sent_date');
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
		Schema::drop('email_campaign');
	}

}
