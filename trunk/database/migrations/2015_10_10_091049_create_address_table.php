<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(Schema::hasTable('address')) return;

		Schema::create('address', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('city_id')->unsigned();
			$table->integer('postcode');
			$table->string('line1');
			$table->string('line2');
			$table->string('line3');
			$table->string('line4');
			$table->integer('area_id')->unsigned();

			/* This format is what Google accepts directly: 3.123, 101.35 */
			/* float becomes double(8,2) by default */
			$table->double('longitude',15,8);
			$table->double('latitude',15,8);

			$table->enum('type',array('default','billing','delivery'))->nullable();
			$table->softDeletes()->nullable;
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
		Schema::drop('address');
	}

}
