<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('org_id')->nullable();
			$table->string('name');
			$table->integer('ordernum')->nullable();
			$table->integer('category_id');
			$table->boolean('public');
			$table->float('cost', 10, 0);
			$table->float('price', 10, 0);
			$table->string('product_code')->nullable()->comment('Barcode can be added here');
			$table->string('sku', 100)->nullable();
			$table->integer('supplier_id');
			$table->integer('product_unit');
			$table->string('product_image');
			$table->integer('alert_qty');
			$table->boolean('enabled')->default(1);
			$table->integer('never_deminishing')->nullable();
			$table->integer('assembled_product')->nullable();
			$table->integer('component_product')->nullable();
			$table->boolean('is_fixed_assets');
			$table->integer('ledger_id')->nullable();
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
		Schema::drop('products');
	}

}
