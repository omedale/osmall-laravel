<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesmemoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('salesmemo', function(Blueprint $table) {
			$table->increments('id')->unsigned();

			/* This is the BUYER */
			$table->integer('fairlocation_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('salesmemo_no')->unsigned();
			$table->enum('status',['active','printed','voided'])->default('active');
			$table->softDeletes();

			/* Order received = created_at */
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
        Schema::drop('salesmemo');
    }
}
