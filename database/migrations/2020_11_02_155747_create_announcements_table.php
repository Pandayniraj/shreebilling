<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('announcements', function(Blueprint $table)
		{
			$table->integer('announcements_id', true);
			$table->integer('org_id')->nullable();
			$table->text('title');
			$table->text('description');
			$table->integer('user_id');
			$table->timestamps(10);
			$table->enum('status', array('published','unpublished'))->default('unpublished');
			$table->enum('placement', array('login','internal','external','email'));
			$table->boolean('view_status')->comment('1=Read 2=Unread');
			$table->date('start_date');
			$table->date('end_date');
			$table->string('share_with', 20)->nullable();
			$table->string('department_id')->nullable();
			$table->string('team_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('announcements');
	}

}
