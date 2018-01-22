@extends('common.default')
@section('opengraph')
@if(Session::has('smm_opengraph'))
<?php
$product=Session::get('smm_opengraph');
?>
<meta property="og:title" content="{{$product->name}}" />
<meta property="og:image" content="{{asset('/images/product/'.$product->id)}}/{{$product->photo_1 }}" />

<meta property="og:description" content="{{$product->description}}" />
<meta property="og:url" content="{{url('cart')}}" />

<?php
Session::forget('smm_opengraph');
?>
@endif
@stop
@section('content')
@def $currency = \App\Models\Currency::where('active', 1)->first()->code
@def $gst_tax_rate= \App\Models\Globals::first()->gst_rate
<?php 
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\IdController;
$subscription = false;
 if(isset($merchant_id)){
	 $subscription = true;
 }
if(isset($isstation)){
  if($isstation == 1){
	  unset($isstation);
  }
} 

?>
<style type="text/css">
	label {
  display: block;
  padding-left: 15px;
  text-indent: -15px;
}
input {
  width: 13px;
  height: 13px;
  padding: 0;
  margin:0;
  vertical-align: bottom;
  position: relative;
  top: -1px;
  *overflow: hidden;
}
.table-noborder{
	border:none;
}
.grayout {
    opacity: 0.5; /* Real browsers */
    filter: alpha(opacity = 50); /* MSIE */
}
.summary{
	font-size: 1em;
	font-weight: bold;
}
.popover{
	max-width: 600px;

}
.popover table{
	border:none;
}
.popoverData{
	max-width: 600px;
}
</style>
<script type="text/javascript"> 

	function number_format(number, decimals, dec_point, thousands_sep) {
		  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		  var n = !isFinite(+number) ? 0 : +number,
		    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		    s = '',
		    toFixedFix = function(n, prec) {
		      var k = Math.pow(10, prec);
		      return '' + (Math.round(n * k) / k).toFixed(prec);
		    };
		  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
		  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		  if (s[0].length > 3) {
		    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		  }
		  if ((s[1] || '')
		    .length < prec) {
		    s[1] = s[1] || '';
		    s[1] += new Array(prec - s[1].length + 1).join('0');
		  }
		  return s.join(dec);
	}
</script>
<input type="hidden" name="refno" value="{{$refNo}}" id="refno">
<div class="container">
  <div class="row" style="margin-bottom:10px;">
	@if(!$subscription)
		<h1>View Cart</h1>
	@else 	
		<h1>Confirm your Subscription</h1>
	@endif
	<div id="cartMessageArea">
	@if($showMessage==1)
		
		<div class="alert alert-danger">
		  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  {!! $cartMessage !!}		</div>
		 
	@endif
	</div>
		@if(Cart::totalItems() < 1)
	    <table class="table table-bordered cart-table" style="margin-top:20px">
		  <thead>
		  	<tr style="background-color: #e0e0e0;">
		  		<th style="text-align:center;">No.</th>
		  		<th style="text-align:center;">Product ID</th>
		  		<th style="text-align:center;">Product Name</th>
		  		<th style="text-align:center;">Unit Price</th>
		  		<th style="text-align:center;">Quantity</th>
		  		<th style="text-align:center; border:none">Amount</th>
		  		<th></th>
		  		<th style="text-align:center; border:none">Merchant Total</th>
		  	</tr>
		  </thead>
		  <tbody>
			  	<tr >
			  		<td colspan="8" style="text-align:center;">
			  			<strong><span id="mcomp"></span>No product in cart</strong>
			  			<span id="moshop"></span>
						<strong class="pull-right"></strong>
			  		</td>				
			  	</tr>					 
		  </tbody>
		 </table>
		 	 
		@else
		
  		{!! Form::open(array('url'=>'https://www.mobile88.com/ePayment/entry.asp', 'class'=>'payment-form', 'id'=>'paymentForm')) !!}
	    <table class="table table-bordered cart-table" style="margin-top:20px">
		  <thead>
		  	<tr style="background-color: #e0e0e0;">
		  		<th style="text-align:center;">No.</th>
		  		<th style="text-align:center;">Product ID</th>
		  		<th style="text-align:center;">Product Name</th>
		  		<th style="text-align:center;">Unit Price</th>
		  		<th style="text-align:center;">Quantity</th>
		  		<th style="text-align:center; border:none">Amount</th>
		  		<th></th>
		  		<th style="text-align:center; border:none">Merchant Total</th>
		  	</tr>
		  </thead>
		  <tbody>
		  	<?php $i = 1; ?>
			<?php $deliver_total = 0; 
				
			?>

		  	@foreach($merchantsAndProducts as $key => $merchants)
				<?php $deliver_n = 0; 
					$delivery_breakup="";
					$merchant=DB::table('merchant')->where('id',$key)->first();
					$showSpecialMessage=false;
					$specialMessage="";
					$oshopp = DB::table('merchantoshop')->join('oshop','merchantoshop.oshop_id','=','oshop.id')->where('merchantoshop.merchant_id',$key)->where('oshop.single',false)->select('oshop.*')->first();
				?>
			  	<tr >
			  		<td colspan="8">

			  			<span id="moshop-{{ $key }}"></span>
						<strong class="pull-right">Merchant ID: {!! IdController::nM($key) !!}</strong>
			  		</td>				
			  	</tr>
			  	@def $sum = 0
			  	@def $c = 1
			  	@def $count = count($merchants)
				
			  	@foreach($merchants as $product)
			  		

			  			<tr class='product_row' data='{{ $product->mid }}'>
				  		<td style="text-align:center"><?php echo $i++; ?></td>
						<td style="text-align:center"><a target="_blank" href="{{ route('productconsumer', $product->parent_id) }}">{!! IdController::nP($product->parent_id) !!}</a></td>
			  			<?php if(!$subscription){ 
			  			?>
						<td style="padding-bottom:0;padding-top:0;text-align:left">			<?php
							if ($product->mode == "rfee") {
								$src=asset('placecards/cartreturnplaceholder.png');
							}else{
								$src="images/product/".$product->parent_id."/".$product->image;
							}
					?>
							<img src="{{$src}}" width='30' height='30' style="padding-top:0;margin-top:4px">				
						&nbsp;
			  			<span style="vertical-align: middle;"> {!! $product->name !!} </span>
			  		</td>
					<?php } else { ?>
					<td style="padding-bottom:8px;padding-top:8px">		  			
			  			{!! $product->name !!}
			  		</td>						
					<?php } ?>
			  		@if($product->page != 'owarehouse' and $product->mode != "rfee")
			  		<td style="padding-bottom:8px;padding-top:8px">
			  			<div class='text-right'><span class='showCurrency'>{{ $currency }}</span> {!! number_format((float)$product->price/100,2) !!}</div>
			  			<input class='eachUnitPrice' data-dir='{!! $product->id !!}' id='unitPrice-{!! $product->id !!}' value='{!! $product->price !!}' type='hidden'>
						<input class='eachDeliveryPrice' data-dir='{!! $product->id !!}' id='deliveryPrice-{!! $product->id !!}' value='{!! number_format($product->delivery_price,2,".","") !!}' type='hidden'>
			  		</td>
			  		<td style='width:14%;padding-bottom:0;padding-top:0'>
					<?php if(!$subscription){ ?>
					<div class="row">
							<div class="col-xs-12"
								style="padding-top:2px; padding-bottom:2px; 
								@if($product->page == 'productconsumerdisc' || $product->mode== 'owish' || $product->mode=='owishbn') {{'display:none; visibility: hidden;'}} @endif">
								<div class="input-group number-spinner">
								<span class="input-group-btn data-dwn">
									<button rel="{{ $key }}" type='button' class="down btn btn-default btn-info" data-dir="{!! $product->id !!}"><span class="glyphicon glyphicon-minus"></span></button>
								</span>
								<input rel="{{ $key }}" data-dir="{!! $product->id !!}" id="'quantity-{!! $product->id !!}" name='eachquantity[{!! $product->id !!}]' type="text" class="ni numberInput-{!! $product->id !!} form-control text-center" value="{!! $product->quantity !!}">
								<span class="input-group-btn data-up">
									<button rel="{{ $key }}" type='button' class="up btn btn-default btn-info" data-dir="{!! $product->id !!}" data-available="{{$product->available}}"><span class="glyphicon glyphicon-plus"></span></button>
								</span>
								</div>
							</div>
							@if($product->page == 'productconsumerdisc' || $product->mode == 'owish' || $product->mode=='owishbn')
							<div class="col-xs-12">
								<center style="margin-top: 8px;">1</center>
							</div>
							@endif
						</div>
					<?php } ?>	
			  		</td>
			  		<td>
			  			<div class='text-right'>
			  				<span>{{ $currency }}</span>
			  				@def $sum = $sum + (($product->price * $product->quantity)/100)
			  				<span class='subPrice-{{ $key }} eachPriceShow-{!! $product->id !!} text-center LOL'>{!! number_format((float)(($product->quantity * $product->price)/100), 2) !!}</span>
			  			</div>
			  			<input data-dir='{!! $product->id !!}' id='ep-{!! $product->id !!}' name='eachprice[{!! $product->id !!}]' type='hidden' class='eachPrice eachprice-{!! $product->id !!}'>
			  		</td>
			  		<input type='hidden' value='' id='oh'/>
			  		@else
			  		<input type='hidden' value='owarehouse' id='oh'/>
			  		<td style="padding-bottom:8px;padding-top:8px">
			  			<div class='text-right'><span class='showCurrency'>{{ $currency }}</span> {!! number_format($product->price/100,2) !!}</div>
			  			<input class='eachUnitPrice' data-dir='{!! $product->id !!}' id='unitPrice-{!! $product->id !!}' value='{!! $product->price !!}' type='hidden'>
			  		</td>
			  		<td style='padding-bottom:8px;padding-top:8px'>
			  			<div class='text-center'>{!! $product->quantity !!}</div>
			  			<input data-dir="{!! $product->id !!}" name='eachquantity[{!! $product->id !!}]' type="text" class="hidden ni numberInput-{!! $product->id !!} form-control text-center" value="{!! $product->quantity !!}">
			  		</td>
			  		<td>
			  			<div class='text-right'>
			  				<span class='showCurrency'>{{ $currency }}</span>
			  				@def $sum = $sum + (($product->price * $product->quantity)/100)
			  				<span class='eachPriceShow-{!! $product->id !!} text-center WHY'>{!! number_format((float)(($product->quantity * $product->price/100)), 2) !!}</span>
			  			</div>
			  			<input data-dir='{!! $product->id !!}' id='ep-{!! $product->id !!}' name='eachprice[{!! $product->id !!}]' type='hidden' class='eachPrice eachprice-{!! $product->id !!}'>
			  		</td>
			  		@endif
			  		<td style="text-align:center">
			  			<a href="cart/remove/{!! $product->identifier !!}"><span style='color:red;'><i class='fa fa-minus-circle'></i></span></a>
			  		</td>
					<?php $deliver_n += $product->actual_delivery_price;
						$delivery_breakup.="<table><tr><td>".IdController::nP($product->id)."</td><td>MYR&nbsp;<span id='".$product->id."'>".number_format($product->delivery_price,2)."</span></td></tr></table>";
					 ?>
					<?php $deliver_total += ($product->delivery_price); ?>
			  		@if ($c == $count)
					<td></td>
					<tr>
						<td colspan="6" style="background-color:#D3D3D3;">
							<div class="row">
								<div class="col-xs-4">
								<a href="javascript:void(0);"
									id="pick" style="color:green;">
									Pick up by yourself?</a>
								</div>
								<div class="col-xs-4">
									<strong>Delivery Fee</strong> 
								</div>
								<div class="col-xs-4">
									<span class="pull-right">
									<a id="popoverData_{{$key}}" class="btn popoverData" href="#" data-content="{{$delivery_breakup}}" rel="popover" data-placement="bottom" data-original-title="Delivery Breakdown" data-trigger="hover">{{$currency}} <span id="totalDeliveryForMerchant-{{$key}}">{{number_format((float)($deliver_n), 2)}}</span>
									</a>
									</span>
								</div>
							</div>
							
							<div class="row">
								
								<div class="col-xs-12">
									
									<a href="javascript:void(0);" id="showSpecialMessage-{{$key}}" style="color:green;" class="showSpecialMessage"></a>
									
								</div>
								
							</div>
							
						
						</td>
						
						<td colspan="2" class="text-center" style="vertical-align: middle;"><span class="pull-right"> {{ $currency }} <span  id='subTotal-{{ $key }}' dcharge="{{$deliver_n}}" gst="0">{{ number_format($sum + (float)$deliver_n, 2) }}</span></span></td>

					</tr>
			  		@else 
					<td></td>	
					@endif
			  		<input type="hidden" class='rowSpan' rel="{{ $key }}" value="{{ $c }}">
			  		<input type="hidden" class='sub_sum' rel="{{ $key }}" value="{{ number_format($sum, 2) }}">
			  		@def $c++
			  	</tr>
			  	@endforeach
		  	@endforeach
			
			<input type="hidden" id="delivery_total" value="{{$deliver_total}}" />
		  
		  </tbody>
		</table>
			<input type="hidden" id="globalvar"  value="{{ $globalvar }}">
			<input type="hidden" id="total"  value="{{ $total }}">
			@if(isset($address->latitude))
			<input type="hidden" id="latitude"  value="{{ $address->latitude }}">
			@endif
			@if(isset($address->longitude))
			<input type="hidden" id="longitude"  value="{{ $address->longitude }}">
			@endif		
			<table class="table table-bordered cart-table" style="margin-top:20px">
				<tr>
				<?php 
				if (Auth::check()) {
					# code...
					if (is_null(Auth::user()->name)) {
						$name=Auth::user()->first_name.' '.
							Auth::user()->last_name;

					} else{
						$name=Auth::user()->name;
					}
				}

				?>
					<td colspan="5">
                        <div class=" box">
                            @if(Auth::check())
                                <strong class="heading"> <a href="javascript:void(0);">Delivery Address</a><a id='editShipping' class="edit" href="javascript:void(0);" style="font-size:.8em;color:red;">&nbsp; &nbsp;Change?</a></strong>
                                <div class="shipping-address">
                                    <strong class="name">{!! $name !!}</strong>
									@if (isset($address))
                                    <address>{!! $address->line1 !!} <br> {!! $address->postcode !!} </address>
									@endif
                                    <abbr title="phone">P:</abbr> {{Auth::user()->mobile_no}}
                                    <br>
                                    <span style="font-style:italic;" class="footer">By placing order, you agree to OpenSupermall's <a href="{{url('terms_cond')}}" target="_blank">Term's & Conditions</a></span>
                                </div>
								@if (isset($address))
                                <input type="hidden" name="address_id" id="address_id" value="{{$address->id}}">
								@endif

                            @else
                            <strong class="heading"><a href="javascript:void(0);">Delivery Address</a></strong>

                            <div class="shipping-address">
                            	<div class="row">
                            	<div class="col-xs-12">
                            	<strong>Not provided</strong>
                            	
                            	<address style="visibility:hidden;">
                            	if you are reading this,<br>
                            	Work for us<br>
                            	OpenSupermall
                            	<br>
                            	<abbr title="Phone">P:</abbr> +123456789
                            	</address>
                            	</div>
                            	</div>
                            	<div class="row">
                            	<div class="col-xs-12">
                            	
                            	<span style="font-style:italic;" class="footer">By placing order, you agree to OpenSupermall's <a href="{{url('terms_cond')}}" target="_blank">Term's & Conditions</a></span>
                            	</div>
                            	</div>
                            </div> 

                            @endif
					</td>
					<td colspan="2">
						{{-- Inception tr td --}}
							<table class="table" id="summary1" >
							<?php 
								
							?>
							
								<tr >
									<td class="summary" style="border:none;">Total</td>
									<td style="border:none;">{{$currency or 'MYR'}}</td>
									<td class="totalPrice" style="border:none;float:right">{{number_format($grandtotal,2)}}</td>
								</tr>
								<tr>
								<?php $max_oc=0;?>
								@if(Auth::check())
								<?php 
								try {
									$max_oc=UtilityController::ocredit()['ocredit'];
									$max_oc=floor($max_oc/100);
								} catch (\Exception $e) {
									
								}

								?>
								
								@endif
									<td class="summary" style="border:none;">OpenCredit</td>
									<td style="border:none;">Points</td>
									<td style="border:none;"><input type="text"
										name="ocredit_part" class="form-control"
										value="0" style="width:90px;float:right;"
										id="ocredit_part" min="0" max="{{$max_oc}}"/></td>
								</tr>
								<tr>
									<td class="summary" style="border:none;">Gross Total <br>(Including {{$gst_tax_rate}}% GST)</td>
									<td style="border:none;">{{$currency or 'MYR'}}</td>
									<td class="grossTotal" style="border:none;float:right">
										{{number_format($grandtotal,2)}}</td>
								</tr>
							</table>
						
					</td>
				</tr>
				<tr>

					<td colspan="7">
					<h5 class="pull-left">OpenCredit: Points {{number_format($ocb/100,2)}}</h5>
					<button href="#" class="btn  btn-primary btn-primary pull-right confirm-onboard"
					id="checkout" type="button">
					<span class="glyphicon glyphicon-check"></span>
					Checkout</button></td>
				</tr>
				<tr>
						
				</tr> 
				<tr class="pmode">
					<td colspan="1">
						<div class="row">
							<div class="col-sm-12 ">
								@include('cart.payment_card')
							</div>
						</div>
					</td>	
					<td colspan="4" id="bank">
						<div class="row">
							<div class="col-sm-12 ">
								@include('cart.payment_bank')
							</div>
						</div>
					</td>
					<td colspan="1">
						<div class="row">
							<div class="col-sm-12">
								@include('cart.payment_paypal')
							</div>
						</div>
					</td>			
				</tr>
				<tr>

					<td colspan="5">
						 <p id="fpx_error_message_amount" style="visibility: hidden;" class="text-danger pull-left">Minimum amount for FPX transaction is MYR 1.00 and Maximum amount is MYR 30,000.00</p>

					 <button type="button" rel_form="" id='makePayment'
						class="btn btn-danger btn-lg pull-right  pmode" style="">
						<span class="glyphicon glyphicon-ok"></span> &nbsp;Pay</button>
						<script type="text/javascript">
							$("#makePayment").click(function(){

								var form=$(this).attr('rel_form');
								var form="fpx_form_id";
								if (form=="" || form =="undefined") {
									toastr.info("Please choose a payment method");
								}

								$('#'+form).submit();
							});
						</script>
					</td>
					<td colspan="2">

						<p class="summary text-right">Total&nbsp;MYR&nbsp;
							<span class='totalPrice totalPrice2'>
							{!! number_format($grandtotal, 2) !!}</span></p>
						<p class="summary text-right">Surcharge&nbsp;{{$currency}}&nbsp;0.00</p>
						<p align="summary " class="text-right"
							style="font-size:1.5em; font-weight:bold;">
							Final&nbsp;Total&nbsp;MYR&nbsp;<span class='totalPrice totalPrice2'>
							{!! number_format($grandtotal, 2) !!}</span></p>				
					</td>
				</tr>
			</table>
			{!! Form::close() !!}
				<input type="hidden" id="Total" value="{{$grandtotal}}">
		@endif
		<p style="text-align: center;"><i><strong>*Disclaimer:</strong> Upon rounding errors, OpenSupermall and it's Merchant reserve the right to revoke the aforesaid deal or transaction.</i></p>
  </div>
</div>
{{-- Hidden Form Area for FPX --}}
<form id="fpx_form_id" action="" method="POST">
	{{-- To be populated after pressing FPX --}}
	{{-- <input type="hidden" name="fpx_buyerBankId" value="TEST0021"> --}}
</form>
	<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document" style="width: 70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Select Station</h4>
				</div>
				<div class="modal-body">
					 <div id="map" class="row" style=" height: 400px"></div>
					 <br>
					 <h4 id="picktext">Your Selected Station: </h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Close</button>
				</div>
				</form>

			</div>
		</div>
	</div>
<br><br>
<div class="modal fade" id="onboardModal" role="dialog"
	style="background-color:white; opacity: 0.93; filter: alpha(opacity=93)">
	<div class="modal-dialog" role="document">

		<div class="modal-body" id="onboardModalBody">
		{{-- Content goes here. --}}
		</div>

	</div>
</div>
@stop

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4nEeGNWNQvpMt-eEud4CENM34fI99jAU "></script>

<script type="text/javascript">
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function string_to_int(argument) {
	res=argument.split(".");
}
$(function() {
	$('#ocredit_part').number(true, 2);
    $('.ni').on('change', function(){
    	item = $(this).attr('data-dir');
    	rel = $(this).attr('rel');
    	val = $('.numberInput-'+item).val();
    	if (val != null)
    	{
	    	$('.numberInput-'+item).val(parseFloat(val));
	    	totalquantity = $('.numberInput-'+item).val();
	    		calculateSum(item, totalquantity,rel);
    	}
    })

	$(document).delegate( '#redirectstation', "click",function (event) {
		var station_id= $('#station_ids').val();

		var url = JS_BASE_URL + '/redirectstation/'+ station_id;

		$.ajax({
			type: "GET",
			url: url,
			dataType: 'json',
			success: function (data) {
		
				window.location.replace(JS_BASE_URL + "/edit_station");
		  	}
		});
	});	
	
    $('.up').click(function(){
    	item = $(this).attr('data-dir');
    	rel = $(this).attr('rel');
    	var available=$(this).attr('data-available');
    	val = $('.numberInput-'+item).val();
    
    	if (parseInt(val) < parseInt(available)) {
	    	$('.numberInput-'+item).val(parseFloat(val)+1);
	    	totalquantity = $('.numberInput-'+item).val();
			calculateSum(item, totalquantity,rel);
    	}else{
    		toastr.warning("You have reached the maximum available quantity for this product.");
    	}
    })

    $('.down').click(function(){
    	item = $(this).attr('data-dir');
    	rel = $(this).attr('rel');
    	val = $('.numberInput-'+item).val();
    	newval = parseFloat(val)-1;

    	if ( newval < 1) {
    		$('.numberInput-'+item).val('1');
    	} else {
			$('.numberInput-'+item).val(parseFloat(val)-1);
    	}
    	totalquantity = $('.numberInput-'+item).val();
		calculateSum(item, totalquantity,rel);
    })

    function calculateSum(item, quantity,rel,verbose = 1) {
	    var controllerPath = JS_BASE_URL+"/cartSum";
        $.ajax({
			type: "POST",
			url: controllerPath,
			data: { id : item, quantity : quantity }, // serializes the form's elements.
			success: function(data) {
			$.ajax({
				type:'GET',
				url:JS_BASE_URL+"/cart/total/items",
				success:function(r){
					$('.badges').text(r);
				}
			});


			// Update the delivery.
			$.each(data.delivery,function(index,value){
				var content="<table class='table-noborder table table-striped'>";
				console.log(value);
				for (var i = value.length - 1; i >= 0; i--) {
					content+="<tr><td>"+value[i][0]+
						"&nbsp;</td><td>MYR&nbsp;"+value[i][1]+"</td></tr>";
				}
				content+="</table>";
				$('#popoverData_'+index).attr("data-content",content);
			});


			// Notify
			$.each(data.notify,function(index,value){
				toastr.warning("Only "+value+" of product "+index+" left. Please reduce the quantity.");
			});

	           	// Change Cart Status
	           	$('.totalPrice').text(number_format(data.total,2));
	           	$('.grossTotal').text(number_format(data.total,2));
	           	$('#Total').val(parseFloat(data.total));
	           	$.each(data.status,function(index,value){
	           		// console.log(index);
	           		var subTotal=number_format(data.status[index].total,2);
	           		var delivery=number_format(data.status[index].delivery,2);
	           		$('#subTotal-'+index).text(subTotal);
	           		$('#totalDeliveryForMerchant-'+index).text(delivery);
	           		$.each(data.status[index].products,function(i,v){
	           			$('.eachPriceShow-'+i).text(number_format(data.status[index].products[i],2));

	           		});

	           	});
	           	$('.showSpecialMessage').each(function(a,b){
	           		$(b).text("");
	           	});
	           	if (verbose == 1) {
		           	$.each(data.adjustments,function(a,b){
		           		// $('#showSpecialMessage-'+a).text("Adjustment of MYR "+data.adjustments[a]+" applied.");
		           	});
		           	toastr.info("Cart successfully updated");
	           	}

	           	
				var ocredit_part=$('#ocredit_part').val();
				if (ocredit_part<0) {ocredit_part=Math.abs(ocredit_part);}
				if (ocredit_part=='NaN') {ocredit_part=0;}
			}
		});



	}
	calculateSum(0,0,0,0);
		var map;
		
		function initialize() {
				var lati = $("#latitude").val();
				var longi = $("#longitude").val();
			  var mapProp = {
				center:new google.maps.LatLng(lati,longi),
				zoom:13,
				mapTypeId:google.maps.MapTypeId.ROADMAP
			  };
			  map=new google.maps.Map(document.getElementById("map"),mapProp);
		}
		// The line below is important. dont delete even though its
		// google.maps.event.addDomListener(window, 'load', initialize);
	
        currency = $('#currency option:selected').text();
        $('.showCurrency').text(currency);

        $('#currency').on('change', function(){
            currency = $('#currency option:selected').text();
            $('.showCurrency').text(currency);
        })

        path = window.location.href;
        var target_path;
        target_path = '{{url("/")}}/';
        $('.select2-container').attr('style','width:100% !important');

        $('#countrySelect').on('change',function(){
            country_code = $(this).val();
            url = target_path+'getState';
            $.post(url, {country_code:country_code}, function(data){
                $('#stateSelect').prop('disabled', false);
                $.each(data, function(key, element) {
                $('#stateSelect').append("<option value='" + key+"'>" + element + "</option>");
                });
            });
        })
        $('#ocredit_part').change(function(){
		// Update grossTotal
		var total=$('#Total').val();
		var oc= $('#ocredit_part').attr('max');
		epsilon=0.01;
		var ocredit_part=$(this).val();
		if (ocredit_part<0) {ocredit_part=Math.abs(ocredit_part);}
		if (ocredit_part=='NaN') {ocredit_part=0;}
		var max = $('#ocredit_part').attr('max');
		@if(Auth::check())
		var message="You don't have enough OpenCredit!";
		@else
		var message="Please login to use OpenCredits";
		@endif
	
		if (parseFloat(ocredit_part)>parseFloat(max) && ocredit_part!=0) {
			toastr.info(message);
		}	
		// console.log(parseFloat(total));
		// console.log(parseFloat(ocredit_part));
		total=parseFloat(total).toFixed(2);
		ocredit_part=parseFloat(ocredit_part).toFixed(2);
		// console.log(total+"|"+ocredit_part);
		var grossTotal=parseFloat(total)- parseFloat(ocredit_part);
		// console.log(grossTotal);
		grossTotal=number_format(grossTotal,2);
		// console.log(grossTotal);
		$('.grossTotal').text(grossTotal);

	});
        $('#stateSelect').on('change',function(){
            state_code = $(this).val();
            url = target_path+'getCity';
            $.post(url, {state_code:state_code}, function(data){
                $('#citySelect').prop('disabled', false);
                $.each(data, function(key, element) {
                $('#citySelect').append("<option value='" + key+"'>" + element + "</option>");
                });
            });
        });

		function addMarkerWithWindow(data, coordinate, map, id) {
			var infowindow = new google.maps.InfoWindow({
				content: data.company_name + '<br>' + data.line1 + '<br>' + data.line2 + '<br>' + data.line3 + '<br>' + data.line4
			});

			var marker = new google.maps.Marker({
				map: map,
				position: coordinate			
			});

			google.maps.event.addListener(marker, 'click', function (e) {
				infowindow.open(map, marker);
				$("#pick_station").val(id);
				$("#stationpicked").val(id);
				$("#picktext").html("Your Selected Station: <b>" + data.company_name + "</b>");
				$("#picktexth").show();
				$("#pickh").html(data.company_name);
				//alert("id" + id);
			});
		}

		$('#unpick').on('click',function(){
			$("#pick_station").val(0);
			$("#picktext").html("Your Selected Station: ");
			$("#picktexth").hide();
			$("#pickh").html("");			
		});

		
        $('#pick').on('click',function(){
			var url = "{{url('payment/get_stations')}}";
			// alert("yey");
			
			var urlbase = $('meta[name="base_url"]').attr('content');

			$.ajax({
				type: "GET",
				url: url,
				dataType: 'json',
				success: function (data) {
				
					if(data.length > 0){
						$("#myModal").modal("show");
						for(i=0;i<data.length;i++){
							var latlng = new google.maps.LatLng(data[i].latitude, data[i].longitude);
							addMarkerWithWindow(data[i],latlng,map, data[i].id);
							/*var marker = new google.maps.Marker({
									position: latlng,
									title: data[i].company_name,
									draggable: true,
									map: map
							});	*/						
						}
					} else {
						$('#pickalert').removeClass('hidden').fadeIn(3000);
					}
				}
			});           
        });

        $('#saveUser').click(function(e) {
            e.preventDefault();
            var controllerPath = target_path+"saveUser";
            $.ajax({
               type: "POST",
               url: controllerPath,
               data: $("#regForm").serialize(),
               success: function(data)
               {
                   if(data=='success')
                   {
                        $('#alert, .success-mg').removeClass('hidden').show().addClass('alert-success');
                        $('.fail-mg').hide();
                   }else if(data=='fail'){
                        $('#alert, .fail-mg').removeClass('hidden').show().addClass('alert-danger');
                        $('.success-mg').hide();
                   }else {
                        $('#alert, .fail-mg').removeClass('hidden').show().addClass('alert-danger').text(data);
                        $('.success-mg').hide();
                   }
               }
             });
        });

        $('#saveAddress').click(function(e) {
            e.preventDefault();
            var controllerPath = target_path+"saveAddress";
            $.ajax({
               type: "POST",
               url: controllerPath,
               data: $("#addressForm").serialize(), // serializes the form's elements.
               success: function(data)
               {
                   alert(data); // show response from the php script.
               }
             });
        });

        $('#editShipping').click(function(e){
            e.preventDefault();
            if($('#shippingTextarea').hasClass('hidden')){
                $('#shippingTextarea').removeClass('hidden').show();
                $('.shipping-address').hide();
                $(this).text('cancel edit');
            }else{
                $('#shippingTextarea').addClass('hidden').hide();
                $('.shipping-address').show();
                $(this).text('edit');
            }
        })
        // This code should deal with form 
        // $('#paymentForm').submit(function(e) {
        // 	// alert("	w");
        // 	e.preventDefault();
        // });
        // Ends

        $('.confirm-onboard').click(function(e){
            e.preventDefault();
            $(this).prop('disabled',true);
            // Check for onboarding required
	
            var url=JS_BASE_URL+"/onboarding/required";
            var onboarding_url=JS_BASE_URL+"/onboarding/init";
            var type="GET";
            $.ajax({
            	url:url,
            	type:type,
            	success:function(r){
            		if (r==1) {
            			$('#onboardModal').modal('show').find('.modal-body').load(onboarding_url);
            		}
            		if (r==2) {
            			var address_modal=JS_BASE_URL+"/cart/new/address";
        	$('#onboardModal').modal('show').find('.modal-body').load(address_modal);
            		}
            		if (r==-1) {
            			var ocredit_part=$('#ocredit_part').val();
            			var ref_no= $('#refno').val();
            			var address_id=$('#address_id').val();
            			if (!ocredit_part) {ocredit_part=0;}
            			var checkout_url=JS_BASE_URL+"/pay/"+ref_no+"/by/ocredit/"+ocredit_part+"/aid/"+address_id;
            			$.ajax({
            				url:checkout_url,
            				type:type,
            				success:function(r){
            					if (r.status=="success") {
            						if (r.short_message==0 || r.short_message==3) {
            							toastr.info('Your order has been paid via OpenCredit. Redirecting');
            							window.location.href=r.redirect;
            							$('.totalPrice2').text(0);
            						}
            						if (r.short_message==1 || r.short_message==-3 || r.short_message==-2 || r.short_message==-11) {
            						
            							$('#checkout').attr('disabled',true);
            							$('.pmode :input').attr('disabled',false);
            							$('.pmode').removeClass('grayout');
            							$('#ocredit_part').attr('disabled',true);
            							$('.totalPrice2').text(r.amount_due);


            							/*New Sorting for Bank. bank_kv is sorted*/
            							fpx_bank="";
            							for (code in r.fpx_bank_kv){
            								if (r.fpx_bank[code] != "undefined") {
            									name=r.fpx_bank_kv[code];
            									val=code;
												bank="<option class='options' value='"+code+"'>"+name;
            									switch (r.fpx_bank[code]) {
													case 'A':
            										fpx_bank+=bank+"</option>";
													break;
            										case 'B':
            										fpx_bank+=bank+" (Offline)</option>";
													break;

												}
            								}  
            								else{
            									// console.log("Missing Bank: "+code)
            								}          								
            							} 
            							if (r.fpx_bank.length!=0) {
            								$('#other_banks').append(fpx_bank);
            							}else{
            								$('#bank').addClass('grayout');
            								$('#bank_error_fpx').text("Payment by Internet Banking is not available. Please use other methods.");
            								$('.payment-errors').show();
            							}
            							

            							
            							 if (r.short_message==1) {
            							
            							// Enable Payment
            								toastr.info('OpenCredit deducted. Pay remainder via other payment methods');
            							}

            							if (r.short_message==-3) {
            								toastr.info("Checkout done. Please pay"	);
            								$('.pmode').focus();
            							}
            						}
            					}
            					if (r.status=="failure") {
            						if (r.short_message == -9) {
            							$.each(r.long_message,function(index,value){
						           		toastr.warning("Only "+value+" of product "+index+" left. Please reduce the quantity.",{timeOut: 5000});
						           		});
            						}
            						if (r.short_message == -5) {
            							toastr.warning(r.long_message);
            								$('#checkout').attr('disabled',false);

            						}
            						if (r.short_message==-1 || r.short_message==-2) {
            							toastr.warning(r.long_message);
            							     $('.pmode :input').attr('disabled',false)
											$('.pmode').removeClass('grayout');
											$('#checkout').attr('disabled',true);
											$('#ocredit_part').attr('disabled',true);
											$('.totalPrice2').text(r.amount_due);
											// $('#makePayment').attr('disabled',false);
            						}
            						if (r.short_message==-4) {
            							toastr.warning(r.long_message);
            						}

            					}
            				}
            			});
            			// location.reload();

            			// $('#paymentForm').submit();

            		}
            	}
            });
      });	
        // CHange Address
        $('#editShipping').click(function(){
        	var address_modal=JS_BASE_URL+"/cart/new/address";
        	$('#onboardModal').modal('show').find('.modal-body').load(address_modal);
        });
	});
</script>
{{-- Code to check if Onboard required --}}
<!-- Code to ajax post the onboarding form-->
<script type="text/javascript">
  $(document).ready(function(){
  	$('.popoverData').popover({
  		html:true
  	});
  	$('#onboard').click(function(){
  		    var url="{{url()}}"+"/onboarding/save";
    var type="POST";
    $.ajax({
      url:url,
      type:type,
      success:function (response) {
          if (response.status=="success") {
              
          }
      }
    });
  	});

  });
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#other_banks').change(function(){
			$('input').removeAttr('checked');
				// $('input #paymentid').val()=$('input[name=payment_id]').val();
			// Refresh the jQuery UI buttonset.                  
			// $( "inp" ).buttonset('refresh');
		});
		// Keep changing value
		$('input[name=payment_id]').change(function(){
					var payment_id=$('input.pid').val();
					// $('input#paymentid').attr('value',payment_id);
	
		});

	});
	// Init disable all
	$('.pmode :input').attr('disabled',true)
	$('.pmode').addClass('grayout');
	$('.pmode').attr('disabled',true).addClass('grayout');
	$('#makePayment').attr('disabled',true);
	// OCredit UI
	$('.pid').change(function() {
		// body...
		$('#makePayment').attr('disabled',false);
	});
if ($('input:radio.pid').is(':checked')) {
	$('.none').attr('selected',true);
}
$("#other_banks").select2({
  minimumResultsForSearch: Infinity
});

$('select.pid').change(function(){
	$('#makePayment').attr('disabled',true);
	$('input:radio.pid').attr('checked',false);
	var bank=$('#other_banks').val();
	var data={
		'bank':bank,
		'refno':$('#refno').val().toString()
	};
	var url="{{url('fpx/checksum')}}";
	$.ajax({
		url:url,
		type:'POST',
		data:data,
		success:function(r){
			if (r.status=="success") {
				// Fill in FPX.
				var fpx_html="";
            							
				$("#fpx_form_id").attr('action',r.fpx_post_url);
				for (key in r.fpx_form) {
					fpx_html+="<input type='hidden' name='"+key+"' value='"+r.fpx_form[key]+"' >";
				};
				// console.log(r.fpx_form.fpx_txnAmount);
				if (r.fpx_form.fpx_txnAmount>30000 || r.fpx_form.fpx_txnAmount <1) {
					$('#fpx_error_message_amount').css('visibility','visible');
				}
				else{
					$('#fpx_form_id').append(fpx_html);
					$('#fpx_error_message_amount').css('visibility','hidden');
					$('#makePayment').attr('disabled',false);
				}
				
			}
		}
	});
});
</script>
	
@stop
