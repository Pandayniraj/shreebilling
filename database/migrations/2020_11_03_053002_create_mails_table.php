<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('lead_id')->unsigned()->default(1);
			$table->integer('user_id')->unsigned()->default(1);
			$table->string('mail_from');
			$table->string('mail_to');
			$table->string('subject');
			$table->text('message');
			$table->enum('type', array('outbound','inbound'));
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
		Schema::drop('mails');
	}

}
