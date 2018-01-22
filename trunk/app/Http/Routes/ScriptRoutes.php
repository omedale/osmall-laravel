<?php
/* OpenSupermall Custom Routes */
	Route::get('/script/testgen', ['as' => 'testgen', 'uses' => 'ScriptController@testgen']);
	Route::get('/script/t1', ['as' => 't1', 'uses' => 'ScriptController@t1']);
	Route::get('/script/t2', ['as' => 't2', 'uses' => 'ScriptController@t2']);
	Route::get('/script/t3', ['as' => 't3', 'uses' => 'ScriptController@t3']);
	Route::get('/script/t4', ['as' => 't4', 'uses' => 'ScriptController@t4']);
	Route::get('/script/t5', ['as' => 't5', 'uses' => 'ScriptController@t5']);
	Route::get('/script/t6', ['as' => 't6', 'uses' => 'ScriptController@t6']);

	Route::get('/script/testuuid', ['as' => 'testuuid', 'uses' => 'ScriptController@testuuid']);
	Route::get('/script/merchant', ['as' => 'scriptmerchant', 'uses' => 'ScriptController@scriptmerchant']);
	Route::get('/script/station', ['as' => 'scriptstation', 'uses' => 'ScriptController@scriptstation']);
	Route::get('/script/seller', ['as' => 'scriptseller', 'uses' => 'ScriptController@scriptseller']);
	Route::get('/script/branch', ['as' => 'scriptbranch', 'uses' => 'ScriptController@scriptbranch']);
	Route::get('/script/buyer', ['as' => 'scriptbuyer', 'uses' => 'ScriptController@scriptbuyer']);
	Route::get('/script/product', ['as' => 'scriptproduct', 'uses' => 'ScriptController@scriptproduct']);
	Route::get('/script/productdetails', ['as' => 'scriptproductdetails', 'uses' => 'ScriptController@scriptproductdetails']);
	Route::get('/script/porder', ['as' => 'scriptporder', 'uses' => 'ScriptController@scriptporder']);
	Route::get('/script/do', ['as' => 'scriptdo', 'uses' => 'ScriptController@scriptdo']);
	Route::get('/script/delivery', ['as' => 'scriptdelivery', 'uses' => 'ScriptController@scriptdelivery']);
	Route::get('/script/discount', ['as' => 'scriptdiscount', 'uses' => 'ScriptController@scriptdiscount']);
	Route::get('/script/ocredit', ['as' => 'scriptocredit', 'uses' => 'ScriptController@scriptocredit']);
	Route::get('/script/hyper', ['as' => 'scripthyper', 'uses' => 'ScriptController@scripthyper']);
	Route::get('/script/openwish', ['as' => 'scriptopenwish', 'uses' => 'ScriptController@scriptopenwish']);
	Route::get('/script/voucher', ['as' => 'scriptvoucher', 'uses' => 'ScriptController@scriptvoucher']);
	Route::get('/script/smm', ['as' => 'scriptsmm', 'uses' => 'ScriptController@scriptsmm']);
	Route::get('/script/cre', ['as' => 'scriptcre', 'uses' => 'ScriptController@scriptcre']);
	Route::get('/script/autolink', ['as' => 'scriptautolink', 'uses' => 'ScriptController@scriptautolink']);
	Route::get('/script/oshop', ['as' => 'scriptoshop', 'uses' => 'ScriptController@scriptoshop']);
	Route::get('/script/oshopid', ['as' => 'scriptoshopid', 'uses' => 'ScriptController@scriptoshopid']);
	Route::get('/script/oshopsingle', ['as' => 'scriptoshopsingle', 'uses' => 'ScriptController@scriptoshopsingle']);
	Route::get('/script/oshopurl', ['as' => 'scriptoshopurl', 'uses' => 'ScriptController@scriptoshopurl']);
	Route::get('/script/floor', ['as' => 'scriptfloor', 'uses' => 'ScriptController@scriptfloor']);
	Route::get('/script/country', ['as' => 'scriptcountry', 'uses' => 'ScriptController@scriptcountry']);
	Route::get('/script/company', ['as' => 'scriptcompany', 'uses' => 'ScriptController@scriptcompany']);
	Route::get('/script/receipt_no', ['as' => 'scriptreceipt_no', 'uses' => 'ScriptController@scriptreceipt_no']);
	Route::get('/script/merchantproduct', ['as' => 'scriptmerchantproduct', 'uses' => 'ScriptController@scriptmerchantproduct']);
	Route::get('/script/qr', ['as' => 'scriptqr', 'uses' => 'ScriptController@scriptqr']);
	Route::get('/script/oshopqr', ['as' => 'scriptoshopqrqr', 'uses' => 'ScriptController@scriptoshopqr']);
	Route::get('/script/productqr', ['as' => 'scriptproductqrqr', 'uses' => 'ScriptController@scriptproductqr']);
	Route::get('/script/productstatus', ['as' => 'scriptproductstatus', 'uses' => 'ScriptController@scriptproductstatus']);
	Route::get('/script/b2bproductstatus', ['as' => 'b2bscriptproductstatus', 'uses' => 'ScriptController@b2bproductstatus']);
	Route::get('/script/productthumbs', ['as' => 'scriptproductthumbs', 'uses' => 'ScriptController@scriptproductthumbs']);
	Route::get('/script/productthumbs2', ['as' => 'scriptproductthumbs2', 'uses' => 'ScriptController@scriptproductthumbs2']);
	Route::get('/script/defaulttokens', ['as' => 'scriptdefaulttokens', 'uses' => 'ScriptController@scriptdefaulttokens']);
	Route::get('/script/defaultwarehouse', ['as' => 'scriptdefaultwarehouse', 'uses' => 'ScriptController@scriptdefaultwarehouse']);
?>
