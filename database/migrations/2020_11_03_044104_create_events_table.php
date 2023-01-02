<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('event_type', array('concert','dinner','lunch','hightea','cocktail','picnic','party','seminar','conference','workshop','galas'));
			$table->integer('venue_id');
			$table->string('event_name', 250);
			$table->dateTime('event_start_date');
			$table->dateTime('event_end_date');
			$table->enum('event_status', array('registered','paid','cancelled','postpone'));
			$table->integer('num_participants');
			$table->decimal('amount_paid', 6);
			$table->decimal('potential_cost', 6);
			$table->decimal('calculated_cost', 6);
			$table->decimal('edited_cost', 6);
			$table->decimal('extra_cost', 6);
			$table->text('comments');
			$table->text('menu_items');
			$table->text('other_details');
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
		Schema::drop('events');
	}

}
