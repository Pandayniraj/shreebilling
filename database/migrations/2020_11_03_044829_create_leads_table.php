<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('lead_type_id')->unsigned()->default(0)->index('leads_lead_type_id_foreign');
			$table->integer('contact_id');
			$table->integer('campaign_id');
			$table->integer('org_id')->nullable();
			$table->integer('stage_id')->nullable();
			$table->float('price_value', 10, 0);
			$table->string('title');
			$table->string('name');
			$table->string('department');
			$table->string('position');
			$table->text('description');
			$table->string('mob_phone')->nullable();
			$table->string('mob_phone2', 200);
			$table->string('home_phone');
			$table->string('address_line_1');
			$table->string('address_line_2');
			$table->string('country');
			$table->string('city');
			$table->string('sector', 100);
			$table->date('target_date');
			$table->decimal('amount', 12);
			$table->string('email');
			$table->string('grade', 50);
			$table->string('qualification');
			$table->string('homepage')->nullable();
			$table->string('skype');
			$table->integer('product_id')->unsigned()->default(0)->index('leads_course_id_foreign');
			$table->string('custom_product')->nullable();
			$table->integer('communication_id')->unsigned()->default(0)->index('leads_communication_id_foreign');
			$table->integer('enquiry_mode_id')->unsigned()->default(0)->index('leads_enquiry_mode_id_foreign');
			$table->integer('status_id')->unsigned()->default(0)->index('leads_status_id_foreign');
			$table->integer('user_id')->unsigned()->default(1)->index('leads_user_id_foreign');
			$table->integer('company_id');
			$table->date('dob');
			$table->string('peroid_attended');
			$table->string('organization');
			$table->date('last_followed_date');
			$table->string('last_followed_by');
			$table->string('rating', 50)->default('orange');
			$table->boolean('enabled')->default(1);
			$table->boolean('email_opt_out')->default(0);
			$table->boolean('viewed')->default(0);
			$table->string('logo')->nullable();
			$table->integer('reason_id');
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
		Schema::drop('leads');
	}

}
