<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsPagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms_pages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title');
			$table->string('slug');
			$table->string('heading');
			$table->text('excerpt');
			$table->text('body');
			$table->string('photo');
			$table->boolean('enabled');
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
		Schema::drop('cms_pages');
	}

}
