<?php 
	$a1style = "width: 100%; height: 450px; border: 2px solid #ccc;";
	if(!is_null($a1)){
		$a1style .= " background-image: url('" .asset('/') . "images/advertisement/" . $a1->id ."/" . $a1->path . "'); background-size: cover;";
	}
	$d1style = "height: 250px; border: 2px solid #ccc;";
	if(!is_null($d1)){
		$d1style .= " background-image: url('" .asset('/') . "images/advertisement/" . $d1->id ."/" . $d1->path . "'); background-size: cover;";
	}
	$d2style = "height: 250px; border: 2px solid #ccc;";
	if(!is_null($d2)){
		$d2style .= " background-image: url('" .asset('/') . "images/advertisement/" . $d2->id ."/" . $d2->path . "'); background-size: cover;";
	}
	$d3style = "height: 250px; border: 2px solid #ccc;";
	if(!is_null($d3)){
		$d3style .= " background-image: url('" .asset('/') . "images/advertisement/" . $d3->id ."/" . $d3->path . "'); background-size: cover;";
	}
	$d4style = "height: 250px; border: 2px solid #ccc;";
	if(!is_null($d4)){
		$d4style .= " background-image: url('" .asset('/') . "images/advertisement/" . $d4->id ."/" . $d4->path . "'); background-size: cover;";
	}
?>
<div class="col-sm-12 no-padding">
	<p style="font-size: 16px; vertical-align: center;" align="center"><a href="javascript:void(0);" class="edit_main_slider">1280 x 450 (Recommended)</a></p>
</div>
<div class="clearfix"></div>
<div class="a1" id="a1" style="{{$a1style}}">
	
</div>
<div class="clearfix"></div>
<div class="col-sm-3 no-padding" class="d1" id="d1" style="{{$d1style}}">
					
</div>
<div class="col-sm-3 no-padding" class="d2" id="d2" style="{{$d2style}}">

</div>
<div class="col-sm-3 no-padding" class="d3" id="d3" style="{{$d3style}}">

</div>
<div class="col-sm-3 no-padding" class="d4" id="d4" style="{{$d4style}}">

</div>
<div class="col-sm-3 no-padding">
	<p style="font-size: 16px; vertical-align: center;" align="center"><a href="javascript:void(0);" class="edit_hyper" rel="1">320 x 250 (Recommended)</a></p>
</div>
<div class="col-sm-3 no-padding">
	<p style="font-size: 16px; vertical-align: center;" align="center"><a href="javascript:void(0);" class="edit_hyper" rel="2">320 x 250 (Recommended)</a></p>
</div>
<div class="col-sm-3 no-padding">
	<p style="font-size: 16px; vertical-align: center;" align="center"><a href="javascript:void(0);" class="edit_hyper" rel="3">320 x 250 (Recommended)</a></p>
</div>
<div class="col-sm-3 no-padding">
	<p style="font-size: 16px; vertical-align: center;" align="center"><a href="javascript:void(0);" class="edit_hyper" rel="4">320 x 250 (Recommended)</a></p>
</div>