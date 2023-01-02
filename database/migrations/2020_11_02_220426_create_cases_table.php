<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cases', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('parent_id');
			$table->integer('lead_id');
			$table->integer('org_id')->nullable();
			$table->integer('client_id')->nullable();
			$table->enum('priority', array('high','low','medium'));
			$table->enum('status', array('new','assigned','closed','pending','rejected'));
			$table->string('type');
			$table->string('subject');
			$table->text('description');
			$table->text('resolution');
			$table->boolean('viewed')->default(0);
			$table->string('attachment');
			$table->string('ticket_name');
			$table->string('ticket_email');
			$table->integer('assigned_to');
			$table->boolean('enabled');
			$table->timestamps(10);
			$table->integer('template_id')->nullable();
			$table->string('name')->nullable();
			$table->string('contact_no')->nullable();
			$table->string('email')->nullable();
			$table->string('job_title')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cases');
	}

}
