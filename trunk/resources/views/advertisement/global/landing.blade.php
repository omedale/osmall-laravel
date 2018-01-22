@extends("common.default")

@section("content")
<div class="container" style="margin-top:30px;">
	@include('admin/panelHeading')
	@include('advertisement/panelHeading')
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-sm-6">
			<h3>Landing Page</h3>
		</div>
		<div class="col-sm-6">
			<a class="preview_advert  btn btn-info pull-right" href="javascript:void(0);">Preview</a>
			<a class="edit_advert btn btn-info pull-right" style="display: none;" href="javascript:void(0);">Edit</a>
		</div>
	</div>	
</div>	
<div class="container-fluid">
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
	<div class="row">		
		<div class="col-sm-12 no-padding" id="edit_view_advert">
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
		</div>
		<div class="col-sm-12" id="preview_view_advert" style="display: none;">
			
		</div>
	</div>
</div>
<br><br><br>
<div class="modal fade" id="myModalHyper" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 55%">
        <div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Hyper Advertisement Edition</h4>
            </div>
            <div class="modal-body">
                <h3 id="modal-Tittle2"></h3>
                <div id="myBody2">
					<form id="hyper_form">
						<div class='col-sm-3'><b>Name</b></div>
						<div class='col-sm-9'><input type="text" name="hyper_name" id="hyper_name" size="40" /></div>
						<div class="clearfix"></div>
						<div class='col-sm-3'><b>URL</b></div>
						<div class='col-sm-9'>{{URL::to('/')}}/<input name="hyper_url" type="text" id="hyper_url" size="30" /></div>
						<div class="clearfix"></div>
						<div class='col-sm-3'><b>Price</b></div>
						<div class='col-sm-3'>MYR <input type="text" name="hyper_price"  id="hyper_price" size="5" /></div>
						<div class="clearfix"></div>
						<div class='col-sm-3'><b>Image</b></div>
						<div class='col-sm-9'><input type="file" name="hyper_image"  id="hyper_image"></div>
						<div class="clearfix"></div>
						<input type="hidden" id="hyper_segment" name="hyper_segment"  value="0" />
						<div class='col-sm-12'><a class="save_hyper btn btn-info pull-right" href="javascript:void(0);">Save Advertisement</a></div>
					</form>
					<br><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>

        </div>
    </div>
</div>	

<div class="modal fade" id="myModalSlider" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 55%">
        <div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Hyper Advertisement Edition</h4>
            </div>
            <div class="modal-body">
                <h3 id="modal-Tittle2"></h3>
                <div id="myBody2">
					<form id="slider_form">
						<div class='col-sm-3'><b>Name</b></div>
						<div class='col-sm-9'><input type="text" name="slider_name" id="slider_name" size="40" /></div>
						<div class="clearfix"></div>
						<div class='col-sm-3'><b>URL</b></div>
						<div class='col-sm-9'>{{URL::to('/')}}/<input name="slider_url" type="text" id="slider_url" size="30" /></div>
						<div class="clearfix"></div>
						<div class='col-sm-3'><b>Price</b></div>
						<div class='col-sm-3'>MYR <input type="text" name="slider_price"  id="slider_price" size="5" /></div>
						<div class="clearfix"></div>
						<div class='col-sm-3'><b>Image</b></div>
						<div class='col-sm-9'><input type="file" name="slider_image"  id="slider_image"></div>
						<div class="clearfix"></div>
						<div class='col-sm-6'>
						<select id="slider" name="slider_number">
							<option value="1">Slide #1</option>
							<option value="2">Slide #2</option>
							<option value="3">Slide #3</option>
							<option value="4">Slide #4</option>
							<option value="5">Slide #5</option>
							<option value="6">Slide #6</option>
						</select>
						</div>
						<div class='col-sm-6'>
							<?php 
								$src1 = "";
								$src2 = "";
								$src3 = "";
								$src4 = "";
								$src5 = "";
								$src6 = "";
								foreach($a1slides as $slides){
									switch ($slides->slider) {
										case 1:
											$src1 = asset('/') . "images/advertisement/" . $slides->id ."/" . $slides->path;
											break;
										case 2:
											$src2 = asset('/') . "images/advertisement/" . $slides->id ."/" . $slides->path;
											break;
										case 3:
											$src3 = asset('/') . "images/advertisement/" . $slides->id ."/" . $slides->path;
											break;
										case 4:
											$src4 = asset('/') . "images/advertisement/" . $slides->id ."/" . $slides->path;
											break;
										case 5:
											$src5 = asset('/') . "images/advertisement/" . $slides->id ."/" . $slides->path;
											break;
										case 6:
											$src6 = asset('/') . "images/advertisement/" . $slides->id ."/" . $slides->path;
											break;
									}
								}
							?>
							<img width="200" height="40" class="slides" id="silde1" src="{{$src1}}" />
							<img width="200" height="40" class="slides" style="display: none;" id="silde2" src="{{$src2}}" />
							<img width="200" height="40" class="slides" style="display: none;" id="silde3" src="{{$src3}}" />
							<img width="200" height="40" class="slides" style="display: none;" id="silde4" src="{{$src4}}" />
							<img width="200" height="40" class="slides" style="display: none;" id="silde5" src="{{$src5}}" />
							<img width="200" height="40" class="slides" style="display: none;" id="silde5" src="{{$src6}}" />
						</div>
						<div class="clearfix"></div>
						<br>
						<div class='col-sm-12'><a class="save_slider btn btn-info pull-right" href="javascript:void(0);">Save Advertisement</a></div>
					</form>
					
					<br><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>

        </div>
    </div>
</div>	
<script type="text/javascript">
	$(document).ready(function(){
		$("#hyper_price").number(true,2);
		$("#slider_price").number(true,2);
		$(document).delegate( '.save_hyper', "click",function (event) {
			var fdata = new FormData($("#hyper_form")[0]);
			$.ajax({
				url: JS_BASE_URL + '/admin/general/advertisement/save_hyper',
				data:fdata,
				async:false,
				type:'post',
				processData: false,
				contentType: false,
				success:function(response){
					$("#myModalHyper").modal('toggle');
					$("#edit_view_advert").html(response);
				},
			});
		});
		
		$(document).delegate( '.save_slider', "click",function (event) {
			var fdata = new FormData($("#slider_form")[0]);
			$.ajax({
				url: JS_BASE_URL + '/admin/general/advertisement/save_slider',
				data:fdata,
				async:false,
				type:'post',
				processData: false,
				contentType: false,
				success:function(response){
					$("#myModalSlider").modal('toggle');
					$("#edit_view_advert").html(response);
				},
			});
		});
		
		$(document).delegate( '#slider', "change",function (event) {
			var slide = $(this).val();
			$(".slides").hide();
			$("#silde" + slide).show();
		});
		$(document).delegate( '.edit_hyper', "click",function (event) {
			var segment = $(this).attr("rel");
			$("#hyper_segment").val(segment);
			$("#myModalHyper").modal('show');
		});
		
		$(document).delegate( '.edit_main_slider', "click",function (event) {
			$("#myModalSlider").modal('show');
		});
		
		$(document).delegate( '.preview_advert', "click",function (event) {			
			var _this = $(this);
			$.ajax({
				url: JS_BASE_URL + '/admin/general/advertisement/landing_preview',
				async:false,
				type:'get',
				success:function(response){
					_this.hide();
					$("#edit_view_advert").hide();
					$(".edit_advert").show();
					$("#preview_view_advert").show();
					$("#preview_view_advert").html(response);
				},
			});
			
		});
		$(document).delegate( '.edit_advert', "click",function (event) {
			$(this).hide();
			$("#edit_view_advert").show();
			$(".preview_advert").show();
			$("#preview_view_advert").hide();
		});
	});
</script>
@stop
