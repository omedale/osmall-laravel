@extends("common.default")

@section("content")
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{asset('css/productbox.css')}}")>
<style type="text/css">
    .productbox{
        margin-right: 10px;
         font-family: 'Lato', sans-serif !important;
       /*  font-weight: normal !important;*/
    }

    .hidden{
        display: none;
    }
.cat-img {
    border: 1px solid #ccc;
    padding: 10%;
}
.cat-img img {
    object-fit: contain;
    height: 160px;
}
b,strong {
    font-weight: 550;
}
</style>
    <style type="text/css">
    /*Very small devices*/
            @media (min-width: 200px) and (max-width: 407px) {
            .productbox{
                font-size: 10px;
            }
			.productbox{
				margin-right: 0px;
				 font-family: 'Lato', sans-serif !important;
			   /*  font-weight: normal !important;*/
			}
        }
        /* Small devices (tablets, 768px and up) */
        @media (min-width: 768px) and (max-width: 991px) {
            .productbox{
                font-size: 8px;
            }
        }
        /* tablets/desktops and up ----------- */
        @media (min-width: 992px) and (max-width: 1199px) {
            .productbox{
                font-size: 10px;
            }
        }
        /* large desktops and up ----------- */
        @media screen and (min-width: 1200px) {
            .productbox{
                font-size: 12.5px;
            }
        }
        .badge{
            font-size: 0.9em !important;
            font-weight: normal;
        }

    </style>
<section class="">
    <div class="container"><!--Begin main cotainer-->

        <div class="row">
			<div class="col-xs-12">
				{!! Breadcrumbs::render('SMM') !!}
			</div>
            <div class="col-xs-12 nomobile">
                <h1>Social Media Marketeer: Product <small class="pull-right">{{ $products->count() }} results</small> </h1>
            </div>

            {{-- sort by section start --}}
            <div class="col-xs-4 pull-right margin-top box-green nomobile">
                <div class="col-xs-6">
                    <div class="row text-right" style="margin-top:5px">
                        <label> Sort By: &nbsp;</label>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="row">
                        <select class="selectpicker"  data-style="btn-green" data-live-search="true">
                            <option > Price Low to High</option>
                            <option > Price High to Low</option>
                            <option > Relevance</option>
                            <option > Discount</option>
                        </select>
                    </div>
                </div>
            </div>
            {{-- sort by section end --}}

            {{-- <div class="clearfix"></div> --}}
            <div class="col-xs-12">
                  <?php $value=3;
                        $av=10;
                        $text=17;
                        $o=4;
                        $row=0;
						$kwsmm=0;
                     ?>
                @foreach($products as $product)
                    <?php $row++;?>
                {{-- NEW PRODUCT BOX --}}

				@if(Auth::check())
					<div  class="col-sm-2 col-xs-6 column productbox added productbox_{{$product->id}}" data-pid="{{$product->id}}">
				@else
					<div class="col-sm-2 col-xs-6 column productbox" data-pid="{{$product->id}}">
				@endif
                            {{-- <a href="{{ route('productconsumer', $product['id']) }}"> --}}
                            <div class="cat-img">
		                        @if(!Auth::check())
									<a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal" class="smm_validation" role="button">
								@endif
									<img src="{{ URL::to('/') }}/images/product/{{$product->id}}/{{$product['photo_1']}}"
									class="added img simg img-responsive full-width"
									data-pid="{{$product->id}}">

								@if(!Auth::check())
									</a>
								@endif
                            </div>
                              {{-- <img class="img-responsive" src="/images/product/{{$product->id}}/{{$product->photo_1}}"/> --}}

							 <div data-tooltip="{{ucfirst($product->name)}}"
							 	class="mouseover producttitle">
								 <div class="gradientEllipsis inside" >
									@if(is_null($product->name) || $product->name == "")
										&nbsp;
									@else
									 {{ucfirst($product->name)}}
									 <div class="dimmer"></div>
									@endif
								 </div>
							 </div>
                            <div class="productprice">
								<div class="pricetext" style="font-size:1em">
                              
                                @if ($product->discounted_price == 0)
                                    @if ($product['retail_price'] != 0)
										{{$currency->code or 'MYR'}}{{number_format($product['retail_price']/100,2)}}
										<br>
										<span>&nbsp;</span>
                                    @endif
                                @else
                                    <span style="font-weight:normal;">{{$currency->code or 'MYR'}}</span><strike style="color:red; font-weight:normal"> <span style="color:#333;">{{number_format($product['retail_price']/100,2)}} </span></strike>
                                    <span style="color:red; font-weight:normal">{{number_format($product['discounted_price']/100,2)}} </span>
                                    <?php

                                         $a= $product->retail_price-$product->discounted_price;
                                        $b= $a/$product->retail_price;
// >>>>>>> a484d839bf440f6e8f615726039fc4ae00234b5a
                                        $discount= $b*100
                                    ?>
                                    <br>
                                    <a href="#" class="pull-right"><span class="badge " style="color:white;background:red;">Save {{number_format($discount,0)}}%</span></a><br>
                                @endif
                            </div>

                            </div>
                        </div>
                {{-- ENDS --}}

                    {{-- <div class="col-xs-2 on-hover-product class-border-product" data-product-id="{{ $product->id }}">
                        <img class="img-responsive"
                             src="{{ asset('images/product/'.$product->id.'/'.$product->photo_1) }}">
                        <h5>{{ $product->name }} <strong
                                    class="pull-right strikethrough">{{ $product->retail_price }}</strong></h5>
                    <strong class="pull-left text-danger">Compulsory</strong>
                        <strong class="discounted-price pull-right text-danger">{{ $product->discounted_price }}<br>
                            -20%</strong> --}}
                    <!--                    <a href="{{URL::to('SMM/facebook')}}" class="btn btn-primary">F</a>-->

                    {{-- <br />
                    <a id="ref_fb"
                       href="" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600');
                           return false;">
                        <img src='' alt="FB"/>
                    </a>
                    <a id="ref_tw" href=""  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600');return false;">
                        <img src="" alt="TW"/>
                    </a>
                    <a id="ref_gp" href=""
                       onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600');return false">
                        <img src="" alt="GP"/>
                    </a>
                    <a id="ref_lkd" href="" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600');
                           return false;">
                        <img src="" alt="LI"/>
                    </a> --}}

                    {{-- <div class="clearfix"> </div> --}}
                {{-- </div> --}}
					<?php $kwsmm++; ?>
					@if($kwsmm == 5)
					<div class="clearfix"> </div>
					<?php $kwsmm=0; ?> 
					@endif 

                @endforeach

                <div class="clearfix"></div>

            </div>
            @if($button_status==True)
            <div class="col-xs-12 margin-top">
                <button title="Share this to your friends " class="btn btn-green pull-right blast-none" id="blast-none" type="button" disabled><i class='fa  fa-spinner  fa-spin hidden'  id="bspin" data-toggle="modal" data-target="#socialModal" data-pid=""></i> SHARE</button>
            </div>
            @endif
        </div>
    </div><!--End main cotainer-->
    {{-- Social Media Modal --}}
@include('modals.smm')
<script type="text/javascript">
    $(document).ready(function(){
        var product_id=-1;
        $('body').click(function(evt){
            if ($(evt.target).hasClass('added')) {
                $('.productbox').removeClass('selected');

                var pid= $(evt.target).attr('data-pid');
                $('#blast-none').attr('data-pid',pid);
                window.product_id=pid;
                var cls=".productbox_"+pid;
                $(cls).toggleClass('selected');
                $('#blast-none').prop('disabled',false);
                // if ($(evt.target).hasClass('selected')) {
                //     var product_id= $('.productbox').attr('data-pid');
                //     alert(product_id);
                // };

            }
            else{
                $('.productbox').removeClass('selected');
                 $('#blast-none').prop('disabled',true);
            }

        });
		 $('.smm_validation').click(function(){
			$("#modal_smm_message").show();
			setTimeout(function () {
				$('#modal_smm_message').hide();
			}, 20000);
		});
    });
</script>

<script type="text/javascript">
    @if (  $role=="mer" and $count==0) 
        $(document).ready(function(){
            toastr.info('As a merchant please sign up for SMM services.');
        });

    @endif
</script>
{{-- ENDS --}}
    <script type="text/javascript">
        $(document).ready(function () {

            var share_url = "http://beta.opensupermall.com/SMM";
            var share_image = "http://beta.opensupermall.com/images/item1.png";
            //full url not ../images should be http://www.
            var title = "Designer Watch";
            var description = "Looking awesome DEsigner watch.";
            //title and description should be formatted ie, remove all special char otherwise it may creates errors on social media sites.
            var FB_url = "http://www.facebook.com/sharer.php?s=100&p[title]=" + (title) + "&p[summary]=" + description + "&p[url]=" + encodeURIComponent(share_url+"?referer_id=1&&media=FB") + "&p[images][0]=" + (share_image);
            var GP_url = "https://plus.google.com/share?url=" + encodeURIComponent(share_url+"?referer_id=1&&media=GP");
            var TW_url = "http://twitter.com/home?status=" + escape(title) + "+" + encodeURIComponent(share_url+"?referer_id=1&&media=TW");
            var Pt_url = "http://pinterest.com/pin/create/bookmarklet/?media=" + encodeURIComponent(share_image) + "&url=" + encodeURIComponent(share_url) + "& is_video=false&description=" + description;
            var TB_url = "http://www.tumblr.com/share/photo?source=" + encodeURIComponent(share_image) + "&caption=" + (description) + "&clickthru=" + encodeURIComponent(share_url+"?referer_id=1&&media=TB");
            var LK_url = "http://www.linkedin.com/shareArticle?mini=true&url=" + encodeURIComponent(share_url) + "&title=" + (title) + "&source=" + encodeURIComponent(share_url+"?referer_id=1&&media=LI");
            $("#ref_fb").attr('href', FB_url);
            $("#ref_gp").attr('href', GP_url);
            $("#ref_tw").attr('href', TW_url);
            //        jQuery("#ref_pr").attr('href', Pt_url);
            //        jQuery("#ref_tum").attr('href', TB_url);
            $("#ref_lkd").attr('href', LK_url);
        });
    </script>
</section>

@stop
