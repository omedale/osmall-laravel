<?php

use Illuminate\Database\Seeder;

class MerchantTable1Seeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */

  public function run()
  {

    \DB::table('merchant')->truncate();
    $now = \Carbon\Carbon::now()->toDateTimeString();
    \DB::table('merchant')->insert(array(
     1 => array(
        'user_id' => '1',
        'company_name' => 'KLEENSO RESOURCES SDN BHD',
        'gst' => 'EXAMPLE',
        'business_reg_no' => '645400-X',
        'country_id' => '150',
        'business_type' => 'sdn_bhd',
        'address_id' => '1',
        'office_no' => '+603 8023 1668',
        'mobile_no' => 'Example',
        'oshop_name' => 'KLEENSO',
        'oshop_logo_1' => 'kleenso60.png',
        'oshop_address_id' => '0',
        'description' => 'Kleenso Resources Sdn Bhd is involved in the manufacturing and marketing of total cleaning solution products. We manufacture full range of cleaning solution products under our house brand Kleenso and also our own Pesso brand of pest repellant products. <br><br> In addition to our own brands, we are also authorised distributors for other trusted brands including 3M, Dupont and ABC.<br><br><h3><u>Our Vision</u></h3><br>To be an innovative manufacturer, to market and promote the knowledge of healthy lifestyle in total cleaning solution products ranging from home care, skin care, car care to industrial care.<br><br><h3><u>Our Mission</u></h3><br>To beautify and enhance humanity through healthy, quality, environmental friendly solutions at affordable prices.',
        'license' => '1',
        'coverage' => 'internationally',
        'ownership' => '0',
        'category_id' => '5',
        'planned_sales' => '0',
        'bankaccount_id' => '1',
        'return_policy' => '<p>Example</p>',
        'osmall_commission' => 10.00,
        'mc_sales_staff_id' => 0,
        'referral_sales_staff_id' => 0,
        'smm_quota_max' => 1,
        'mc_sales_staff_commission' => 0,
        'mc_with_ref_sales_staff_commission' => 0,
        'referral_sales_staff_commission' => 0,
        'mcp1_sales_staff_id' => 5,
        'mcp2_sales_staff_id' => 6,
        'psh_sales_staff_id' => 7,
        'mcp1_sales_staff_commission' => 0,
        'mcp2_sales_staff_commission' => 0,
        'smm_sales_staff_commission' => 0,
        'psh_sales_staff_commission' => 0,
        'str_sales_staff_commission' => 0,
        'created_at' => $now,
        'updated_at' => $now,
      )
    ,
      22 => array(
        'user_id' => '2',
        'company_name' => 'try2',
        'gst' => 'EXAMPLE',
        'business_reg_no' => 'try2',
        'country_id' => '150',
        'business_type' => 'sdn_bhd',
        'address_id' => '2',
        'office_no' => 'try2',
        'mobile_no' => 'Example',
        'oshop_name' => 'Ideahom',
        'oshop_logo_1' => 'Ideahom.png',
        'oshop_address_id' => '0',
        'description' => 'try2',
        'license' => '1',
        'coverage' => 'internationally',
        'ownership' => '0',
        'category_id' => '5',
        'planned_sales' => '0',
        'bankaccount_id' => '2',
        'return_policy' => '<p>Example</p>',
        'osmall_commission' => 10.00,
        'mc_sales_staff_id' => 1,
        'referral_sales_staff_id' => 4,
        'smm_quota_max' => 1,
        'mc_sales_staff_commission' => 4,
        'mc_with_ref_sales_staff_commission' => 3,
        'referral_sales_staff_commission' => 1,
        'mcp1_sales_staff_id' => 6,
        'mcp2_sales_staff_id' => 7,
        'psh_sales_staff_id' => 8,
        'mcp1_sales_staff_commission' => 1,
        'mcp2_sales_staff_commission' => 2,
        'smm_sales_staff_commission' => 10,
        'psh_sales_staff_commission' => 3,
        'str_sales_staff_commission' => 4,
        'created_at' => $now,
        'updated_at' => $now,
      )
    ,
      2 => array(
        'user_id' => '3',
        'company_name' => 'try3',
        'gst' => 'EXAMPLE',
        'business_reg_no' => 'try3',
        'country_id' => '150',
        'business_type' => 'sdn_bhd',
        'address_id' => '3',
        'office_no' => 'try3',
        'mobile_no' => 'Example',
        'oshop_name' => 'Thermos',
        'oshop_logo_1' => 'Thermos.png',
        'oshop_address_id' => '0',
        'description' => 'try3',
        'license' => '1',
        'coverage' => 'internationally',
        'ownership' => '0',
        'category_id' => '5',
        'planned_sales' => '0',
        'bankaccount_id' => '3',
        'return_policy' => '<p>Example</p>',
        'osmall_commission' => 10.00,
        'mc_sales_staff_id' => 2,
        'referral_sales_staff_id' => 3,
        'smm_quota_max' => 1,
        'mc_sales_staff_commission' => 4,
        'mc_with_ref_sales_staff_commission' => 3,
        'referral_sales_staff_commission' => 1,
        'mcp1_sales_staff_id' => 5,
        'mcp2_sales_staff_id' => 6,
        'psh_sales_staff_id' => 7,
        'mcp1_sales_staff_commission' => 3,
        'mcp2_sales_staff_commission' => 2,
        'smm_sales_staff_commission' => 10,
        'psh_sales_staff_commission' => 3,
        'str_sales_staff_commission' => 4,
        'created_at' => $now,
        'updated_at' => $now,
      )
    ,
      3 => array(
        'user_id' => '4',
        'company_name' => 'try4',
        'gst' => 'EXAMPLE',
        'business_reg_no' => 'try4',
        'country_id' => '150',
        'business_type' => 'sdn_bhd',
        'address_id' => '4',
        'office_no' => 'try4',
        'mobile_no' => 'Example',
        'oshop_name' => 'Osafe Fire In',
        'oshop_logo_1' => 'Osafe Fire In.png',
        'oshop_address_id' => '0',
        'description' => 'try4',
        'license' => '1',
        'coverage' => 'internationally',
        'ownership' => '0',
        'category_id' => '5',
        'planned_sales' => '0',
        'bankaccount_id' => '4',
        'return_policy' => '<p>Example</p>',
        'osmall_commission' => 10.00,
        'mc_sales_staff_id' => 2,
        'referral_sales_staff_id' => 1,
        'smm_quota_max' => 1,
        'mc_sales_staff_commission' => 3,
        'mc_with_ref_sales_staff_commission' => 4,
        'referral_sales_staff_commission' => 1,
        'mcp1_sales_staff_id' => 5,
        'mcp2_sales_staff_id' => 6,
        'psh_sales_staff_id' => 7,
        'mcp1_sales_staff_commission' => 5,
        'mcp2_sales_staff_commission' => 2,
        'smm_sales_staff_commission' => 5,
        'psh_sales_staff_commission' => 2,
        'str_sales_staff_commission' => 2,
        'created_at' => $now,
        'updated_at' => $now,
      ),
      4 => array(
        'user_id' => '5',
        'company_name' => 'try5',
        'gst' => 'EXAMPLE',
        'business_reg_no' => 'try5',
        'country_id' => '150',
        'business_type' => 'sdn_bhd',
        'address_id' => '5',
        'office_no' => 'try5',
        'mobile_no' => 'Example',
        'oshop_name' => 'Minotti',
        'oshop_logo_1' => 'Minotti.png',
        'oshop_address_id' => '0',
        'description' => '',
        'license' => '1',
        'coverage' => 'internationally',
        'ownership' => '0',
        'category_id' => '5',
        'planned_sales' => '0',
        'bankaccount_id' => '5',
        'return_policy' => '<p>Example</p>',
        'osmall_commission' => 10.00,
        'mc_sales_staff_id' => 4,
        'referral_sales_staff_id' => 3,
        'smm_quota_max' => 1,
        'mc_sales_staff_commission' => 4,
        'mc_with_ref_sales_staff_commission' => 3,
        'referral_sales_staff_commission' => 1,
        'mcp1_sales_staff_id' => 6,
        'mcp2_sales_staff_id' => 7,
        'psh_sales_staff_id' => 8,
        'mcp1_sales_staff_commission' => 3,
        'mcp2_sales_staff_commission' => 2,
        'smm_sales_staff_commission' => 5,
        'psh_sales_staff_commission' => 3,
        'str_sales_staff_commission' => 4,
        'created_at' => $now,
        'updated_at' => $now,
      )
    ));
  }
}
