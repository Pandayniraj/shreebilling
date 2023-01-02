<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('ticket_number');
			$table->integer('user_id');
			$table->string('from_user')->nullable()->comment('user from external source');
			$table->string('from_email')->nullable();
			$table->string('from_ext', 100)->nullable();
			$table->string('from_phone')->nullable();
			$table->text('cc_users');
			$table->boolean('notice');
			$table->string('source');
			$table->string('help_topic');
			$table->integer('department_id');
			$table->string('sla_plan');
			$table->date('due_date');
			$table->integer('assign_to');
			$table->string('issue_summary', 500);
			$table->text('detail_reason');
			$table->integer('ticket_status')->comment('1=open,2=resolved,3=closed');
			$table->text('internal_notes');
			$table->enum('form_source', array('internal','external'));
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
		Schema::drop('tickets');
	}

}
