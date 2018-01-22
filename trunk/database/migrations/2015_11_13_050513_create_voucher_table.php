<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/* Table to store product vouchers:
		 * product:voucher = 1:m */

        Schema::create('voucher', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->unsigned();

			/* Validity of Voucher:
			 * 1. Whole Year
			 * 2. Whole Month
			 * 3. Whole week
			 * 4. Weekdays only
			 * 5. Weekends only */
			$table->enum('validity',
				array('wyear','wmonth','wweek','v2'))->nullable();
				
		$table->enum('applicable',
				array('wkdays','wkends'))->nullable();

			/* Weekly duration:
			 * 1 -> week 1
			 * 2 -> week 2
			 * 3 -> week 3
			 * 4 -> week 4
			 * 5 -> week 5
			 * 6 -> week 6 */
			/*
			$table->enum('weekly_duration',
				array('1','2','3','4','5','6'))->nullable();
			 */

			/* Availability */
			//$table->date('availability');

			/* Duration */
			//$table->date('start_duration');
			//$table->date('end_duration');

			/* Address of location */
			$table->integer('address_id')->unsigned();

			/* Merchant:Product  = m:n, => merchantproduct table */
			/* SpcialPrice => merchantproduct table */     

			/* Product:Wholesale (Unit/Price) = 1:n, => wholesale table */

			/* Category:Specification = 1:m */
			/* Subcategory L1:Specification = 1:m */
			/* Subcategory L2:Specification = 1:m */
			/* Subcategory L3:Specification = 1:m */

			/* Product Details is in the parent product record */
			//$table->string('remarks');

			/* Status for a voucher */
			$table->enum('status', ['active','expired','executed']);

			/* Where this voucher came from */
			$table->enum('source', ['smm','openwish','direct']);

			/* Reference number (acts like a password) */
			$table->string('reference_no', 15);
			$table->integer('unit')->unsigned()->default(1);
			$table->integer('validity_year')->unsigned()->default(1);

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
        Schema::drop('voucher');
    }
}
