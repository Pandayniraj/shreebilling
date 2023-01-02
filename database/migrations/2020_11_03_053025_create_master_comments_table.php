<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('master_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('file')->nullable();
			$table->enum('type', array('case','project_task','bugs','kb'));
			$table->integer('master_id');
			$table->integer('user_id');
			$table->text('comment_text');
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
		Schema::drop('master_comments');
	}

}
