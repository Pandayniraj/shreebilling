<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms_contacts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('full_name');
			$table->string('email');
			$table->string('guidence');
			$table->string('phone');
			$table->text('message');
			$table->string('status');
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
		Schema::drop('cms_contacts');
	}

}
