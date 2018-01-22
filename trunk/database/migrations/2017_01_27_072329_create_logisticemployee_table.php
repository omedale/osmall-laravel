<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticemployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
			Schema::create('logisticemployee', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('logistic_id')->unsigned();

				$table->integer('employee_id')->unsigned();

				$table->softDeletes();
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
			Schema::drop('logisticemployee');
		}
}
