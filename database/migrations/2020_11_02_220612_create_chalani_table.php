<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChalaniTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chalani', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->date('date');
			$table->string('letter_num');
			$table->date('letter_date');
			$table->string('subject', 500);
			$table->text('content');
			$table->string('receiver_org');
			$table->integer('ticket_id');
			$table->string('remarks', 500);
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
		Schema::drop('chalani');
	}

}
