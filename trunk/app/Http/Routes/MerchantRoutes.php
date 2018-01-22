<?php


Route::get('merchant/cre/{porderId}','CREController@initRForm');
Route::get('merchant/approval/{porderId}','CREController@showApproveModal');
Route::post('merchant/approval','CREController@doApproval');
?>