<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDartaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('darta', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('darta_num');
			$table->string('received_letter_num');
			$table->date('letter_date');
			$table->integer('sender_office_name');
			$table->string('subject', 500);
			$table->text('content');
			$table->integer('receiving_officer_name');
			$table->date('received_date');
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
		Schema::drop('darta');
	}

}
