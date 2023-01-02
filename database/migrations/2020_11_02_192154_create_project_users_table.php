<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id');
			$table->integer('project_id')->unsigned()->index('project_users_project_id_foreign');
			$table->integer('user_id')->unsigned()->index('project_users_user_id_foreign');
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
		Schema::drop('project_users');
	}

}
