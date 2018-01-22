<?php
	Route::group(array('prefix'=>'test'),function(){

		Route::post('fpx/be','FPXController@test_post_be');



	});

?>