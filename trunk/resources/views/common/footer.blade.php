<!--Begin Footer-->
<style>
@media only screen and (min-width: 599px){
	.footerqr{
		margin-top: -70px;
	}
}
.footer_top ul li {
	padding:2px 0;
	border-bottom: hidden;
	font-size: 13px;
	font-family: Arial, Helvetica, sans-serif;
	letter-spacing: 1px
}
.footerqr{margin-top: 20px;}

h1 {
    display: block;
    font-size: 2.6em;
    margin-top: 0;
    margin-bottom: 10px;
    margin-left: 0;
    margin-right: 0;
    //font-weight: bold;
}
</style>

<footer class='ofooter mobile' style="background-color: #0a0909;">
	 <div class="logo-headermobile text-center" >
        <div class="container">
            <div class="row">
                <div class="col-xs-6" style="margin-top:10px;">
                   <img class="img-responsive" alt="Logo"
				   	style="height:120px;object-fit:contain"
				   	src="{{asset('/')}}images/footer-logo.png">
				</div>
				<div class="col-xs-6" style="margin-top:10px;">
                   <?php 
					$qr = DB::table('landingqr')->join('qr_management','qr_management.id','=','landingqr.qr_management_id')->orderBy('landingqr.id','DESC')->first();
				?>
					@if(!is_null($qr))
						<img src="{{URL::to('/')}}/images/qr/landing/{{$qr->image_path}}.png" class="pull-right footerqr" width="120px" style="margin-top: 20px;" />

					@endif

				</div>
				<div class="col-xs-12 copyright text-center">
					<p style="margin-top:-10px; font-size: 12px;">
					&copy; Intermedius OpenSupermall Sdn. Bhd.
					<small>(1144993-D)</small>
					All Rights Reserved 2015.
					</p>
				</div>
				
			</div>
		</div>
    </div>
</footer>
<footer class='ofooter nomobile' style="background-color: #0a0909;">
    <div class="logo-header  text-center" >
        <div class="container">
            <div class="row">
                <div class="col-sm-12" style="margin-top:0;">
                   <img class="img-responsive" alt="Logo"
				   	style="height:120px;object-fit:contain"
				   	src="{{asset('/')}}images/footer-logo.png">
				</div>
				<br>
				<div class="col-sm-12 copyright text-center">
					<p style="margin-top:-10px;">
					&copy; Intermedius OpenSupermall Sdn. Bhd.
					<small>(1144993-D)</small>
					All Rights Reserved 2015.
					</p>
				</div>
			</div>
		</div>
    </div>
	<br><br>
    <div class="container footer_top">
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <ul>
                    <li><h3>About OpenSupermall</h3></li>
                    {{-- <li>{!! link_to('/about_us','About Us') !!}</li> --}}
					{{--
                    <li><a href="javascript:void(0);"
						class="cover_show_as_modal">.</a></li>
					--}}
                    <li><a href="javascript:void(0);"
						class="about_us_show_as_modal">About Us</a></li>
                    <li>{!! link_to_route('job.index','Job') !!}</li>
<!--
                    <li>{!! link_to_route('advertise.index','Advertise with Us') !!}</li>
-->
                    <li>{!! link_to_route('terms_cond.index','Terms & Conditions') !!}</li>
                    <li>{!! link_to_route('privacy.index','Privacy Policy') !!}</li>
                </ul>
            </div>
            <div class="col-sm-3 col-md-3">
                <ul>
                    <li><h3>Subscription</h3></li>
                    <li>
						<a href="javascript:void(0);"
						rel-href="{{ route('buyerreg') }}"
						rel-id="buyerlink"
						class="prevent"> Buyer Registration</a>
					</li>
					@if (Auth::check() && Auth::user()->hasRole('mer'))
                    <li>
                        <a href="javascript:void(0);"
						rel-href="{{url('create_new_merchant')}}"
						rel-id="merchantlink"
						class="prevent"> Merchant Registration </a>
                    </li>
                    @else
					<li>
                        <a href="javascript:void(0);"
						rel-href="{{url('create_new_merchant')}}"
						rel-id="merchantlink"
						class="prevent"> Merchant Registration </a>
					</li>
                    @endif

 					@if (Auth::check() && Auth::user()->hasRole('sto'))
					<li>
						<a href="javascript:void(0);"
						rel-href="{{url('create_new_station')}}"
						rel-id="stationlink"
						class="prevent"> Station Registration</a>
					</li>
					@else
					<li>
						<a href="javascript:void(0);"
						rel-href="{{ route('create-station') }}"
						rel-id="stationlink"
						class="prevent"> Station Registration</a>
					</li>
					@endif
 					<li>
						<a href="javascript:void(0);"
						rel-href="{{url('create_new_fairmode')}}"
						rel-id="fairlink"
						class="prevent"> Fair Mode Registration</a>
					</li> 
  					<li>
						<a href="javascript:void(0);"
						rel-href="{{url('create_new_humancap')}}"
						rel-id="humancaplink"
						class="prevent"> Employee Benefit Registration</a>
					</li>  

                </ul>
				<br><br>

            </div>
            <div class="col-sm-3 col-md-3">
                 <ul>
                    <li><h3>Help Center</h3></li>

				{{--
					<li>{!! link_to_route('directory.index',"Directory") !!}</li>
				--}}
                    {{--<li>{!! link_to_route('openSupport',"OpenSupport") !!}</li>--}}

					<li><a href="{{route('openSupportIndex')}}">OpenSupport</a></li>
					<li><a href="{{route('weaccept')}}">We Accept</a></li>
					<li><a href="javascript:void(0);=" class="investment">Investment</a></li>
					<li>{!! link_to_route('downloads.index',"Download Apps") !!}</li>
                    <li>{!! link_to_route('newsletter.index',"News") !!}</li> 

				{{--
                    <li>{!! link_to_route('buyerhelp.index',"Help the Buyer") !!}</li>
                    <li>{!! link_to_route('sellerhelp.index',"Help the Seller") !!}</li>
                    <li>{!! link_to_route('feedback.index',"Feedback") !!}</li>
                    <li>{!! link_to_route('contactus.index',"Contact Us") !!}</li>

                    <li>{!! link_to('howtobuy','How to Buy') !!}</li>
                    <li>{!! link_to('howtosell','How to Sell') !!}</li>
                    <li><a href="#">How to Return</a></li>
				--}}
                </ul>
            </div>
		
			<!--
			<div class="clearfix"></div>
            <div class="col-sm-4 social-links">
                <ul class="list-inline">
                    <li><a href="http://facebook.com/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://linkedin.com/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="https://twitter.com/" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="http://www.weixin.com/" target="_blank"><i class="fa fa-weixin"></i></a></li>
                    <li><a href="http://www.weibo.com/" target="_blank"><i class="fa fa-weibo"></i></a></li>
                </ul>
            </div>
			-->
       

				<div class="col-sm-3 col-md-3 text-right">
				<?php 
					$qr = DB::table('landingqr')->join('qr_management','qr_management.id','=','landingqr.qr_management_id')->orderBy('landingqr.id','DESC')->first();
				?>
					<table class="table">

					<tr style="border:none !important;">
 						<td style="border:none !important;padding-right:0">
							<a href="https://www.facebook.com/OpenSupermall"
							target="_blank">
							<img src="{{asset('images/icons/fb.png')}}"
							class=""
							style="height:60px;width:60px;margin-top:80px;padding-right:0;position:relative;left: 0px"/>
							</a> 
						</td>
					@if(!is_null($qr))
						<td style="border:none !important;padding-left:0">
						<img
						src="{{URL::to('/')}}/images/qr/landing/{{$qr->image_path}}.png"
						class="pull-right footerqr"
						width="120px"
						style="margin-top: 20px;padding-left:0" />
						</td>
					@endif
					</tr>

					<!--
					<tr style="border:none !important">
					<td style="border:none !important">
					<a href="https://www.facebook.com/OpenSupermall" target="_blank">
					<img src="{{asset('icons/fb.png')}}"
					class="pull-right"
					style="height: 30px;width:30px;margin-right:90px;"/>
					</a>
					</td>
					</tr>
					-->
					</table>
				</div>	
        </div>
    </div>
    <style type="text/css">
    	.footerImage img{width: 35px}
    </style>
    
</footer>


<span id="top-link-block" class="hidden thumbnail">
    <a href="#top" class=" btn btn-lg btn-green"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="fa fa-angle-double-up fa-lg"></i>
    </a>
</span><!-- /top-link-block -->

<script type="text/javascript">
    $(document).ready(function(){
        $('.investment').click(function(e){
			$("#myModalInvestment").modal('show');
		});
        $('.prevent').click(function(e){
            e.preventDefault;
            var url_to_redir=$(this).attr('rel-href');
            var a_id=$(this).attr('rel-id');
            $.ajax({
                type:'GET',
                url:JS_BASE_URL+"/check/login",
                success:function(r){
                    if (r.status=="failure") {
						if(a_id == "buyerlink"){
							 window.location.replace(url_to_redir);
						} else {
							if(a_id == "stationlink"){
								$("#myModalCheckstation").modal("show");
							}
							if(a_id == "merchantlink"){
								//$("#myModalCheckmerchant").modal("show");
								window.location.replace(url_to_redir);
							}
							if(a_id == "humancaplink"){
								//$("#myModalCheckmerchant").modal("show");
								window.location.replace(url_to_redir);
							}
							if(a_id == "fairlink"){
								//$("#myModalCheckmerchant").modal("show");
								window.location.replace(url_to_redir);
							}
						}

                    }
                    if (r.status=="success") {
                        var m= "Please logout to register for the service.<br> <button id='mogout' class='btn btn-warning' type='button' url_to_redir='"+url_to_redir+"'>Logout</button>";
                        toastr.info(m);
						$('#mogout').click(function(){
							// alert("lol");
							var url_to_redir=$(this).attr('url_to_redir');
							$.ajax({
								url:JS_BASE_URL+"/logout/user",
								type:'GET',
								success:function (r) {
									// alert(r);
									if (r.status=="success") {
										if(a_id == "buyerlink"){
											 window.location.replace(url_to_redir);
										} else {
											if(a_id == "stationlink"){
												$("#myModalCheckstation").modal("show");
											}
											if(a_id == "merchantlink"){
												window.location.replace(url_to_redir);
											}
										}
									}
								}
							});
						});
                    }
                },
            });

        });

    });
    </script>

<div class="modal fade" id="myModalInvestment" role="dialog" aria-labelledby="myModalRemarks">
	<div class="modal-dialog" role="remarks" style="width: 50%">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" align="center" id="myModalLabel">Investment Opportunities</h4>
		</div>

			<div class="row" style="padding: 15px;">
				<div class="col-md-12" style="">
					<p>We are an ambitious startup, we need more investors to support us. We are not a listed company in any stock market.</p>					
					<div class="col-md-6" style="">
						<input type="text" class="form-control" id="invs_first_name" placeholder="First Name" />
					</div>
					<div class="col-md-6" style="">
						<input type="text" class="form-control" id="invs_last_name" placeholder="Last Name" />
					</div>
					<div class="clearfix"></div><br>
					<div class="col-md-12" style="">
						<input type="text" class="form-control" id="invs_company_name" placeholder="Company Name" />
					</div>
					<div class="clearfix"></div><br>
					<div class="col-md-10" style="">
						<input type="text" class="form-control" id="invs_url" placeholder="e.g. http://myurl.com" />
					</div>
					<div class="clearfix"></div><br>
					<div class="col-md-8" style="">
						<select class="form-control" id="invs_country">
							<?php $countries = DB::table('country')->get();?>
							@foreach($countries as $country)
								<option value="{{$country->id}}"> {{$country->name}} </option>
							@endforeach
						</select>
					</div>
					<div class="clearfix"></div><br>
					<div class="col-md-8" style="">
						<input type="text" class="form-control" id="invs_email" placeholder="e.g. example@opensupermall.com" />
					</div>
					<div class="col-md-4" style="">
						<input type="text" class="form-control" id="invs_mobile" placeholder=" e.g. 0121234567 " />
					</div>
					<div class="clearfix"></div><br>
					<div class="col-md-8" style="">
						<select class="form-control" id="invs_type">
								<option value="accreditor"> Accreditor </option>
								<option value="angel"> Angel </option>
								<option value="institutional"> Institutional </option>
								<option value="other"> Other </option>
						</select>
					</div>
					<div class="clearfix"></div><br>
					<div class="col-md-12" style="">
						<textarea rows="5" style="width:100%;" id="invs_description" placeholder="Describe more here..."></textarea>
					</div>
					<div class="clearfix"></div><br>
					<center><a href="javascript:void(0);" class="btn btn-primary invs_btn"  style="width:200px;">Submit</a></center>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>	
	
<div class="modal fade" id="myModalCheckstation" role="dialog" aria-labelledby="myModalRemarks">
	<div class="modal-dialog" role="remarks" style="width: 50%">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			<img src="{{asset('images/logo.png')}}"
				style="width:50%"/>

			{{--
			<h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
			--}}
		</div>

			<div class="row" style="padding: 15px;">
				<div class="col-md-12" style="">
					<p>We are an e-commerce company, we welcome merchants to become our business partners to offer products and services to our precious customers. If your company type is as listed below, please choose:</p>
					<center>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/logistic" class="btn btn-success choose-company" style="width:400px;">Logistic Company</a>
						<br>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/carpark" class="btn btn-success choose-company"  style="width:400px;" disabled>CarPark Management Company</a>
						<br>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/cafe" class="btn btn-success choose-company"  style="width:400px;" disabled>Cafe & Dining Establishment</a>
						<br>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/online" class="btn btn-success choose-company" style="width:400px;">Online Shopping</a>
						<br>
						<br>
						<a href="javascript:void(0);" class="btn btn-primary proceed-btn"  style="width:200px;">Proceed</a>
					</center>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalCheckmerchant" role="dialog" aria-labelledby="myModalRemarks">
	<div class="modal-dialog" role="remarks" style="width: 50%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<img src="{{asset('images/logo.png')}}"
					style="width:50%"/>
 
 				{{--
				<h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
				--}}
			</div>
					<p>We are an e-commerce company, we welcome merchants to become our business partners to offer products and services to our precious customers. If your company type is as listed below, please choose:</p>
					<center>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/logistic" class="btn btn-success choose-company" style="width:400px;">Logistic Company</a>
						<br>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/carpark" class="btn btn-success choose-company"  style="width:400px;">CarPark Management Company</a>
						<br>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_station/cafe" class="btn btn-success choose-company"  style="width:400px;">Cafe Dinning Establishment</a>
						<br>
						<a href="javascript:void(0);" rel-href="{{url('')}}/create_new_merchant" class="btn btn-success choose-company"  style="width:400px;">Online Shopping</a>
						<br>
						<br>
						<a href="javascript:void(0);" class="btn btn-primary proceed-btn"  style="width:200px;">Proceed</a>
					</center>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


{{-- Cover Page Modal --}}
<div id="cover_modal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="padding:0;width:1020px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button"
			class="close"
			data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cover</h4>
      </div>
      <div class="modal-body"
	  	style="padding:0 !important">
        
      </div>
      <div class="modal-footer">
        <button type="button"
			class="btn btn-default"
			data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
 

{{-- About US Modal --}}

{{-- <div id="about_us_modal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="padding:0;width:1000px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button"
			class="close"
			data-dismiss="modal">&times;</button>
        <h4 class="modal-title">About Us</h4>
      </div>
      <div class="modal-body"
	  	style="width:1000px;margin-left:0 !important; margin-right:0 !important;padding:0 !important; ">
        
      </div>
      <div class="modal-footer" style="padding-right:35px !important">
        <button type="button"
			class="btn btn-default"
			data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> --}}

<script type="text/javascript">
	$(document).ready(function(){
		$('.about_us_show_as_modal').click(function(){
			
			var url =JS_BASE_URL+"/about_us";
			var w = window.open(url, "popupWindow",
				'width=1000, height=815, toolbar=no, location=no, status=no, menubar=no, resizable=yes, scrollbars=yes');
			w.focus();
			return false;
		});
			/*
			$('#about_us_modal').find(".modal-body").load(url);
			$('#about_us_modal').modal('show');
			// Eliminate the annoying left margin in Firefox stable!
			$('#about_us_modal .modal-body').
				css({'width':'1000px','margin-left':'0','margin-right':'0'});
		});

 		$('.cover_show_as_modal').click(function(){
			
			var url =JS_BASE_URL+"/cover";
			$('#cover_modal').find(".modal-body").load(url);
			$('#cover_modal').modal('show');
		}); 
		*/
	});
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.1.1/masonry.pkgd.min.js"></script>
<script>
	$(document).ready(function(){
		$('.invs_btn').click(function(){
			var first_name = $("#invs_first_name").val();
			var last_name = $("#invs_last_name").val();
			var company_name = $("#invs_company_name").val();
			if((first_name == "" && last_name) || company_name == ""){
				toastr.error("Please, fill in First and Last name or Company Name");
			} else {
				var url = $("#invs_url").val();
				var email = $("#invs_email").val();
				var mobile = $("#invs_mobile").val();
				var country = $("#invs_country").val();
				var type = $("#invs_type").val();
				var description = $("#invs_description").val();
				if(email == ""){
					toastr.error("Please, fill in your email");
				} else {
					var formData = {
						first_name: first_name,
						last_name: last_name,
						company_name: company_name,
						url: url,
						email: email,
						mobile: mobile,
						country: country,
						type: type,
						description: description
					};
					$.ajax({
						type: "POST",
						url: JS_BASE_URL + "/investment",
						data:formData,
						success: function (data) {
							toastr.info("Thank you for your interest! We'll be in touch with you!");
							$("#myModalInvestment").modal('toggle');
						},
						error: function (error) {
							toastr.warning("There was an unexpected error, please contact OpenSupport!");
							console.log(error);
						}

					});	
				}
			}
		});
		$('.choose-company').click(function(){
			$('.choose-company').removeClass('selected-company');
			$('.choose-company').css( "background-color" , "#8CC73F");
			$( this ).css( "background-color" , "#449d44" );
			$( this ).addClass( 'selected-company');
		});

		$('.proceed-btn').click(function(){
			var url_ref = $('.selected-company').attr('rel-href');
			if(url_ref == null){
				toastr.warning("Please, choose your company services")
			} else {
				window.location.replace(url_ref);
			}
		});

		$('#link_create_station').button().click(function(){
			$("#myModalCheckstation").modal("show");
		});

		$('#link_create_merchant').button().click(function(){
			$("#myModalCheckmerchant").modal("show");
		});
	});

</script>
<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			url:JS_BASE_URL+"/landing/header/hyper",
			type:'GET',
			success:function(r){
				$('#headerHyper').append(r);
			}
		});

		$.ajax({
			url:JS_BASE_URL+"/landing/header/category",
			type:'GET',
			success:function(r){
				//console.log("SUCCEESSSSSS");
				$('#headerCategory').append(r);
				$.ajax({
					url:JS_BASE_URL+"/landing/header/category_mobile",
					type:'GET',
					success:function(r){
						$('.mobilesubcategories').append(r);
						//console.log(r);
					}
				});
			}
		});
		
		$.ajax({
			url:JS_BASE_URL+"/landing/header/smm",
			type:'GET',
			success:function(r){
				$('#headerSMM').append(r);
			},
			error:function(r){

			}
		});

		$('#show-category-list').click(function (e) {
			e.stopPropagation();
			$('.masonry-megamenu').fadeOut('fast');
			$('#headerCategory .masonry-megamenu').fadeIn('fast', function() {
				//console.log($(this));
				$(this).masonry({
					itemSelector: '.col-xs-3',
					columnWidth: '.col-xs-3',
					containerStyle: null
				});
			});
		});

		$('#show-smm-list').click(function (e) {
			e.stopPropagation();
			$('.masonry-megamenu').fadeOut('fast');
			$('#headerSMM .masonry-megamenu').fadeIn('fast', function() {
				$(this).masonry({
					itemSelector: '.col-xs-3',
					columnWidth: '.col-xs-3',
					containerStyle: null
				});
			});
		});

		$('#show-hyper-list').click(function (e) {
			e.stopPropagation();
			$('.masonry-megamenu').fadeOut('fast');
			$('#headerHyper .masonry-megamenu').fadeIn('fast', function() {
				$(this).masonry({
					itemSelector: '.col-xs-3',
					columnWidth: '.col-xs-3',
					containerStyle: null
				});
			});
		});
		
		$('#show-brand-list').click(function (e) {
			e.stopPropagation();
			$('.masonry-megamenu').fadeOut('fast');
			$('#headerBrand').fadeIn('fast');
			//$('.brand-tabs a:first').tab('show');
		});
		
		$('.brand-tabs a').hover(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});
		
		
		$(window).click(function () {
			$('.masonry-megamenu').fadeOut('fast');
		});

		

	});
</script>
<?php 
 $mgl = DB::table('global')->first();
?>
{{--<input type="hidden" id="max_img_size" value="{{$mgl->max_img_size}}" />--}}
@yield('footer_scripts')
@include('common.login')
