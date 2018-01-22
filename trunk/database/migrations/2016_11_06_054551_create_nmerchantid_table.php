<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNmerchantidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nmerchantid', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('nmerchant_id')->unique();
			$table->integer('user_id')->unsigned();
			$table->integer('merchant_id')->unsigned();
			$table->softDeletes();
			$table->timestamps();
			$table->engine = "MyISAM";
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nmerchantid');
    }
}
