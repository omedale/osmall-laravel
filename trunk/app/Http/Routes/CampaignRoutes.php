<?php
 
Route::get('campaign/{id}','CRMController@show_campaign_webview');
Route::post('campaign/channel/save','CRMController@save_campaign_channel');