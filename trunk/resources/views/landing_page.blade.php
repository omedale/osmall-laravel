@extends('common.default')
@section('opengraph')
<meta property="og:title" content="Welcome to OpenSupermall.com!!"/>
<meta property="og:image" content="{{url()}}/images/footer-logo-blk.png"/>
{{-- <meta property="og:image:width" content="80"/>
<meta property="og:image:height" content="80"/> --}}
<meta property="og:url" content="{{url()}}"/>

<meta property="og:description" content="OpenSupermall is a new generation of eCommerce portal that covers branding, marketing, sales and business solutions. Its business partners are selected based on their respective reputations in the various areas and the primary emphasis are an product authencity, customer satisfaction."/>

@stop
@include('partials._flash',['key'=> Config::get('messages.key.name')])
		@section('content')
		<?php
function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth', 'Seventh', 'Eighth', 'Ninth', 'Tenth', 'Eleventh',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}
		?>
<style type="text/css">
    .floor-numbering{
        z-index: 999 !important;
    }
	.category_desc{
	font-weight:bold;
	font-size:110%;
	color: black;
	font-family: "LatoLatin Light";
	font-size: 30px;
	font-style: normal;
	font-variant: normal;
	font-weight: 500;
	line-height: 26.4px;

	}

	.discription {
    position: relative;line-height: 1.5em; height: 3em;overflow: hidden;
	font-weight:normal;
	color: #666;
   
    text-overflow: ellipsis;
    width: 100%;

}
</style>
<script>
$(document).ready(function(){
    $('.static-tab ul li a').on('click', function(){
        $('html,body').animate({scrollTop: $( $(this).attr('href') ).offset().top}, 1200);
    });
});
$(window).scroll(function() {
	 /*var scroll = $(window).scrollTop();
	if(scroll > 100){
		$('#ahr').show(1000);
	}if(scroll <= 100){
		$('#ahr').hide(1000);
	}
*/
});
    
</script>
<script type="text/javascript" src="{{asset('js/jsslide.min.js')}}"></script>
 <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/productbox.css"/>
<div class="maincontent-area">

 <div class="container mobile">
	<div class="row">
		<div class="col-xs-12" id="ahr" style="margin-bottom: 50px; margin-top: 50px;">
			@foreach($cat_random_product_mob as $mobile_products)
				<div class="boxrow4" style="border-style: hidden;">
					<div class="col-sm-2 col-xs-6" style="border: 0px;">
						<div class="col-xs-12 no-padding" >
							<?php
								$raw_orip = $mobile_products->retail_price;
								$raw_retp = $mobile_products->discounted_price;
							
								($raw_orip > 0) ? $orip = $raw_orip/100 : $orip = 0;
								($raw_retp > 0) ? $retp = $raw_retp/100 : $retp = 0;
							?>
							@if ($retp > 0)
								<span class="badge dispprice" style="padding: 6px 12px !important;">{!! $currency !!}&nbsp;{{number_format($retp,2)}}</span>
							@else
								@if ($orip > 0)
									<span class="badge dispprice" style="padding: 6px 12px !important;">{!! $currency !!}&nbsp;{{number_format($orip,2)}}</span>
								@endif
							@endif
							@if ($orip > $retp && $retp != 0)
								<span class="badge-cutoff"><strong>
								@if ($orip > 0)
								{{--*/
									$res = number_format((($orip - $retp) / $orip) * 100);
									if ($res < 0) $res = 0;
								/*--}}  
								@else
								{{--*/ $res = 0; /*--}}  
								@endif
								<span>{{$res}}</span>%</strong><br>off</span>
							@endif
							<a href="{{ route('productconsumer', [$mobile_products->id]) }}"> 
								<img src="/images/product/{{$mobile_products->id}}/thumb/{{$mobile_products->thumb_photo}}"
								alt="Missing" class="img-responsive" style="border-style: hidden;" />
							</a>
						</div>	
						<?php $pdesc = str_replace('"','&quot;',$mobile_products->name); ?>
						<div data-tooltip="{{$pdesc}}" class="mouseover producttitle col-xs-12">
							<div class="discription inside" style="">
								{{ $mobile_products->name}}										
							</div>
						</div>	
					</div>				
				</div>
			@endforeach
		</div>
	</div>
 </div>
 <div class="container nomobile">

        <div class="row">
       
			<!-- Paul Maker: Commented on 05 April 2017 at 8:15 PM -->
			
            <!--<div data-spy="scroll" class="static-tab" style="display: none;">
                <div class="text-center tab-arrow"><span class="fa fa-sort"></span></div>
                <div class="floor-directory">
                <ul class="nav nav-pills nav-stacked">
                    {{-- @foreach($category_data as $categories)
                        <li class="floor-navigation"><a href="#{{$categories['name']}}">
                                <span class="btn-elevator">{{$categories['floor']}}F</span>
                                <span class="back">{{$categories['desc']}}</span>
                            </a>
                        </li>
                    @endforeach ---}}
                </ul>
                </div>
            </div>-->
       
            <div class="col-sm-12">

                @foreach($adSlot_data as $products)

                    @if($products['placement'] =="t1")
                            <div class="boxrow1">
                        @foreach($products['Products'] as $product)
                                <div class="col-xs-9 nopadding">
                                    <img class="img-responsive" src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['adslot_id']}}" />
                                </div>
                        @endforeach
                    @endif
                    @if($products['placement'] =="t2")
                         @foreach($products['Products'] as $product)
                            <div class="col-xs-3 nopadding">
                                <img class="img-responsive" src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['photo_1']}}" />
                            </div>
                         @endforeach
                        <div class="clearfix"></div>
                            </div>
                    @endif
                    @if($products['placement'] =="t3")
                        <div class="boxrow2">
                        @foreach($products['Products'] as $product)
                            <div class="col-xs-3 nopadding" style="border:solid 1px lightgrey">
                                <img  src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['thumb_photo2']}}" class="img-responsive" />
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                        </div>
                    @endif
                    @if($products['placement'] =="t4")
                    <div class="boxrow3">
                        @foreach($products['Products'] as $product)
                            <div class="col-xs-2 nopadding" style="border:solid 1px lightgrey;margin-bottom: 10px;">
                                <img  src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['thumb_photo2']}}" class="img-responsive" />
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                    @endif
                @endforeach

            <div class="col-xs-12" id="ahr" style="margin-bottom: 50px">
                <div id="all-floors">
					<?php $fll = 1; ?>
                    @foreach($category_data as $categories)
						@if(count($categories['random_photos']) > 0 || !is_null($categories['latest_photo_id']))
							<div class="col-xs-12 nopadding floor" >
								<article id="{{$categories['name']}}" class="floor-content">
									<div class="row floor-board" style="background-color: white;color: black">

										<a href="/floor/{{$categories['floor']}}" style="color:black">
										
										<div class="floor-level" style="background-color: white">
											<div class="col-xs-1" > <span class="fa fa-tablet hide"></span>
											   <img src="{{url('/')}}/images/category/logo/{{$categories['logo_white']}}"
												alt="{{$categories['logo_white']}}" class="img-responsive cate-img"
												style="height:auto; padding-top:-10px;"></div>

											<div class="col-md-offset-2 col-xs-12 col-md-6 text-center category_desc hidden-xs hidden-sm"
												style="border-bottom: 1px solid #b9b9b9;margin-left: 198px">
												{{$categories['desc']}} <div class="text-center" style="font-size: 17px;letter-spacing: 1px;color: #101010;padding-top: 5px;">{{convertNumberToWord($fll)}}Floor</div>
												</div>

	<div class="col-md-offset-2 col-xs-12 col-md-5 text-center category_desc hidden-md hidden-lg"
												style="border-bottom: 1px solid #b9b9b9;">
												{{$categories['desc']}} <div class="text-center" style="font-size: 17px;letter-spacing: 1px;color: #101010;padding-top: 5px;">{{convertNumberToWord($fll)}}Floor</div>
												</div>
											<div class="col-xs-12 col-md-2 text-right view_more pull-right">
												<a href="/floor/{{$categories['floor']}}"
												class="view-more" style="color: black;padding-top: 10px;">View More</a></div>
											<div class="clearfix"></div>
										</div>
										</a>


									</div>
									<div class="row">
										<div style="margin-bottom: 20px;">
											<div class="panel-body no-padding-top no-padding-bottom ">
												<div class="row">
											<!-- 		<div class="boxrow4-l col-sm-4 col-xs-12 no-padding floor-border specialbox" >
														<a href="{{-- URL::to('12') --}}">
														<a href="/floor/{{$categories['floor']}}">
															<img src="/images/product/{{$categories['latest_photo_id']}}/thumb/{{$categories['latest_photo']}}" alt="Missing" class="img-responsive"/>
														</a>
													</div> -->
													<div class="col-sm-12 col-xs-12 no-padding ">
														<div class="boxrow4" style="border-style: hidden;">
														<?php
														$i=0;
														?>
															@foreach($categories['random_photos'] as $random_photos)
															<?php
															if ($i==6) {
																break;
															}
															?>
																<div class="col-sm-2 col-xs-6" style="border: 0px;">
																<div class="col-xs-12 no-padding" >
																	<?php
																		$raw_orip = $random_photos->retail_price;
																		$raw_retp = $random_photos->discounted_price;
																	
																		($raw_orip > 0) ? $orip = $raw_orip/100 : $orip = 0;
																		($raw_retp > 0) ? $retp = $raw_retp/100 : $retp = 0;
																	?>

																	@if ($retp > 0)
																		<span class="badge dispprice" style="padding: 6px 12px !important;">{!! $currency !!}&nbsp;{{number_format($retp,2)}}</span>
																	@else
																		@if ($orip > 0)
																			<span class="badge dispprice" style="padding: 6px 12px !important;">{!! $currency !!}&nbsp;{{number_format($orip,2)}}</span>
																		@endif
																	@endif
																	@if ($orip > $retp && $retp != 0)
																		<span class="badge-cutoff"><strong>
																		@if ($orip > 0)
																		{{--*/
																			$res = number_format((($orip - $retp) / $orip) * 100);
																			if ($res < 0) $res = 0;
																		/*--}}  
																		@else
																		{{--*/ $res = 0; /*--}}  
																		@endif
																		<span>{{$res}}</span>%</strong><br>off</span>
																	@endif

																	<a href="{{ route('productconsumer', [$random_photos->product_id]) }}"> 
																		<img src="/images/product/{{$random_photos->product_id}}/thumb/{{$random_photos->thumb_photo}}"
																		alt="Missing" class="img-responsive" style="border-style: hidden;" />
																	</a>
										</div>	
											<?php $pdesc = str_replace('"','&quot;',$random_photos->name); ?>
											<div data-tooltip="{{$pdesc}}" class="mouseover producttitle col-xs-12">
											<div class="discription inside" style="">
											{{ $random_photos->name}}
											
											</div>
										</div>	
																</div>
															
																						
																<?php
																$i++;
																?>
															@endforeach
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
								</article>
							</div>
							<?php $fll++; ?>
						@endif
                        @foreach($adSlot_data as $products)
                                @foreach($products['Products'] as $product)
                                    @if($categories['floor']== 3 and $products['placement'] =="t5")
                                    <div class="row">
                                        <div class="col-xs-12 nopadding">
                                            <img src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['adslot_id']}}" class="img-thumbnail">
                                        </div>
                                    </div>
                                    @endif
                                    @if($categories['floor']== 6 and $products['placement'] =="t6")
                                    <div class="row">
                                        <div class="col-xs-12 nopadding">
                                            <img src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['adslot_id']}}" class="img-thumbnail">
                                        </div>
                                    </div>
                                    @endif
                                    @if($categories['floor']== 10 and $products['placement'] =="t7")
                                    <div class="row">
                                        <div class="col-xs-12 nopadding">
                                            <img src="/images/product/{{$product['id']}}/thumb/{{$product['thumb_photo2']}}" alt="{{$product['adslot_id']}}" class="img-thumbnail">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                        @endforeach
						
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@stop
