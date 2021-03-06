<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOccupationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('occupation', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('name');	
			$table->string('description');	
			$table->timestamps();

			$table->engine = 'MYISAM';
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('occupation');
	}

}
