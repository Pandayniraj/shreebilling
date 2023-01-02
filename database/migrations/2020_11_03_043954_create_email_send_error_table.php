<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSendErrorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_send_error', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('lead_id')->unsigned()->index('email_send_error_lead_id_foreign');
			$table->integer('email_campaign_id')->unsigned()->index('email_send_error_email_campaign_id_foreign');
			$table->string('email');
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
		Schema::drop('email_send_error');
	}

}
