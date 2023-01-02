<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposals', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('client_lead_id')->nullable();
			$table->integer('contact_id')->nullable();
			$table->integer('org_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->enum('client_type', array('lead','client'))->nullable();
			$table->integer('product_id');
			$table->enum('type', array('proposal','contract'));
			$table->string('subject');
			$table->text('body');
			$table->string('template', 30);
			$table->enum('status', array('draft','paused','negotiating','finalized','sold'));
			$table->boolean('enabled')->default(1);
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
		Schema::drop('proposals');
	}

}
