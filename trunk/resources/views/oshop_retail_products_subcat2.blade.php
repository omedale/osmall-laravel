    <style type="text/css">
	
		.discription {
			position: relative;line-height: 1.5em; height: 3em;overflow: hidden;
			color: #666;
			text-overflow: ellipsis;
			width: 100%;

		}
	
		.col-xs-15,
		.col-sm-15,
		.col-md-15,
		.col-lg-15 {
			position: relative;
			min-height: 1px;
			padding-right: 10px;
			padding-left: 10px;
		}

		.col-xs-15 {
			width: 20%;
			float: left;
		}	
        {{-- start --}}
        hr{
            border-top-color: #5F6879;
            margin-top: 0px;

        }

        .priceTable thead tr th,
        .priceTable tbody tr td {
            padding: 0px;
            border: 0px;
            font-size: 12px;
        }

        .priceTable thead tr th {
            padding-bottom: 5px;
        }

        .list-inline{
            margin-top: 10px;
        }

		.showAlert{
            padding: 2px 5px;
            font-size: 12px;
            border-radius: 20px;
        }

        .product-name{
            font-weight: bold;
            @if(Auth::check())
                border-bottom: 1px solid #ccc;
            padding-bottom: 7px;
            padding-top: 7px;
            @else
                padding-top: 9px;
        @endif
    }

        .qty-area{
            padding-top: 7px;
            padding-bottom: 7px;
            border-bottom: 1px solid #ccc;
        }

        .tier-price {
            padding-top: 4px;
            padding-bottom: 0px;
            height: 100px;
            overflow: hidden;
        }

        .tier-price div p {
            padding-bottom: 0px;
            margin-bottom: 2px;
            font-size: 12px;
            font-weight: bold;
        }

        .productName{
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .product-price {
            font-weight: bold;
            padding-top: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .popover {
            width: 16%;
        }

        @media (max-width: 321px) {
            .popover {
                width: 70%;
            }
        }

        .popover-content {
            padding: 9px 25px;
        }

        .popover-title {
            padding: 9px 10px;
        }


        .list-inline li {
            width: 30px;
            height: 30px;
            border-radius: 2px;
            text-align: center;
            padding-top: 2px;
        }
        .save {
            background: red;
            color: #fff;
            padding-left: 7px;
            border-radius: 20px;
            padding-right: 7px;
            padding-bottom: 3px;
        }

        .p-box-content {
            padding: 0px 8px 0px 8px;
        }

        button.btn-xs{
            padding: 4px 5px !important;
        }

	{{-- stop --}}
		.col-xs-15,
        .col-sm-15,
        .col-md-15,
        .col-lg-15 {
            position: relative;
            min-height: 1px;
            padding-right: 10px;
            padding-left: 10px;
        }

        .col-xs-15 {
            width: 20%;
            float: left;
        }
        @media (min-width: 768px) {
            .col-sm-15 {
                width: 20%;
                float: left;
            }
        }
        @media (min-width: 992px) {
            .col-md-15 {
                width: 20%;
                float: left;
            }
        }
        @media (min-width: 1200px) {
            .col-lg-15 {
                width: 20%;
                float: left;
            }
        }
        .btn-subcat{
            border: none;
            background: #fff;
            padding-left: 0px;
        }

        .fa-li {
            position: static;
        }
    </style>
<div class="col-sm-12" style="margin-bottom:10px">
    <div class="row nomobile">
        <h2 style="margin-left:8px;margin-bottom:0;margin-top:0">Retail</h2>
    </div>
</div>
<?php $kw=0; ?>
<?php $page = 0; ?>
<?php $oproducts = 0; ?>
@if(sizeof($products)>0)
    <?php $value = 4;$av = 12; $text = 25; $o = 3;?>
	<div class="row" id="{{$subcat2->name}}">
		<div class="col-sm-12">
			@if(isset($subcat))
				<h2 class="title" style="margin-left:8px;margin-top:0;">{{$subcat->description}}</h2>
				<h3 class="title" style="margin-left:8px;margin-top:0;">{{$subcat2->description}}</h3>
			@else
				<h2 class="title" style="margin-left:8px;margin-top:0;">{{$subcat2->description}}</h2>
			@endif
			<div class="row cat-items boxrow4">
			@for($j=0; $j < sizeof($products); $j++)
				<?php $product =  $products[$j]; ?>
				@if($oproducts == 0) <div id="page{{$page}}" class="pages" @if($page > 0) style="display: none;" @endif> @endif
								<div class="col-md-2 col-sm-4 col-xs-6">
									<div class="col-xs-12 no-padding">
										<?php
											$raw_orip = $product->retail_price;
											$raw_retp = $product->discounted_price;
										
											($raw_orip > 0) ? $orip = $raw_orip/100 : $orip = 0;
											($raw_retp > 0) ? $retp = $raw_retp/100 : $retp = 0;
										?>

										@if ($retp > 0)
											<span class="badge dispprice" style="padding: 6px 12px !important;">MYR&nbsp;{{number_format($retp,2)}}</span>
										@else
											@if ($orip > 0)
												<span class="badge dispprice" style="padding: 6px 12px !important;">MYR&nbsp;{{number_format($orip,2)}}</span>
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

										<a href="{{ route('productconsumer', [$product->id]) }}"> 
										<img src="{{ URL::to('/') }}//images/product/{{$product->id}}/thumb/{{$product->thumb_photo}}"
											alt="Missing" class="img-responsive timg" style="border-style: hidden;" />
										</a>
								</div>
								<?php $pdesc = str_replace('"','&quot;',$product->name); ?>
								<div data-tooltip="{{$pdesc}}" class="mouseover producttitle col-xs-12">
									<div class="discription inside" style="">
									{{ $product->name}}
									
									</div>
								</div>	
							</div>
				<?php $oproducts++; ?>
					@if($oproducts >=12 )
						<?php $oproducts = 0; ?>
						<?php $page++; ?>
						</div>
					@endif
					<?php $kw++; ?>
					@if($kw == 4)
						<div class="clearfix"> </div>
						<?php $kw=0; ?>	
					@endif				
			@endfor
	 @if($oproducts > 0 )
			</div>
	 @endif
	 </div>
	 <div class="clearfix"> </div>
	<center >
		@if($page > 0 )
			<ul class="pagination">
				<li><a href="javascript:void(0)" class="first_page fontsize"><<</a></li>
				<li><a href="javascript:void(0)" class="prev_page fontsize">< Previous</a></li>
				<li><span  class="last_ellipsis fontsize" style="display: none;">...</span><li>
				@for($pp = 0; $pp <= $page; $pp++)
					@if($pp > 5 && $pp == $page)
						<li><span  class="ellipsis fontsize">...</span><li>
					@endif
						<li><a href="javascript:void(0)" id="apage{{ $pp }}" rel="{{$pp}}" class="fontsize apage @if($pp == 0) selecteda @endif" @if($pp >= 5 && $pp != $page ) style="display: none;" @endif>{{$pp + 1}}</a></li>						
				@endfor
				<li><a href="javascript:void(0)" class="next_page fontsize"> Next ></a></li>
				<li><a href="javascript:void(0)" class="last_page fontsize">>></a></li>
			</ul>

			<input type="hidden" value="{{$page}}" id="page_count" />
			<input type="hidden" value="0" id="current_page" />
		@endif
	</center>			
		</div>
	</div>
@endif
