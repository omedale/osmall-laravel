<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adtarget', function (Blueprint $table) {
            $table->increments('id');

            // Target ->Short form , smm or lpage
            $table->enum('target', ['category','brand','lpage']);

            // Description ->Redable description, Landing Page for lpage
            $table->string('description');

            // Route. Not absolute. eg: foo/bar and not /foo/bar 
            $table->string('route');
            // Defaults
            $table->integer('height');
            $table->integer('width');
            
            $table->softDeletes();
            $table->timestamps();
            $table->engine = "MYISAM";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('adtarget');
    }
}
