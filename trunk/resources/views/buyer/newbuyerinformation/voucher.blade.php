<div class="table-responsive col-sm-12">
	<h2>Voucher</h2>
	<div class="row" style="margin-bottom: 10px">
		<div class="col-md-10">
			<div class="pull-right">
				<label>	
					Search
				</label>
				<input type="text"  />	
			</div>
		</div>

	</div>
	@foreach($voucher_data as $i)
	<div class="row" style="margin-bottom: 10px">
		<div class="col-md-7 col-sm-3" style="background-color: #D7E748;min-height: 164px">
			<?php
				$vp_retail_price=0;
				$vp_discounted_price=0;
				$discount=0;
				if (isset($i['voucher_product'])) {
					$vp_retail_price=$i['voucher_product']->retail_price;
					$vp_discounted_price=$i['voucher_product']->discounted_price;
					if ($vp_retail_price!=0) {
						$discount=number_format((($vp_retail_price-$vp_discounted_price)/$vp_retail_price)*100,1);
					}
				}
			?>
			<p>
				<span class="col-md-3" >Retail Price</span>
				<span class="col-md-3" >{{$currency->code or 'MYR'}} {{number_format(($vp_retail_price)/100,2)}} /pax</span>
				<span class="col-md-5" >Voucher ID: [{{str_pad($i['voucher']->id, 10, '0', STR_PAD_LEFT)}}]</span>
			</p>
			<p>
				<span class="col-md-3" ></span>
				<span class="col-md-3" >Date:</span>
				<span class="col-md-5" >{{$i['voucher_timeslot']? date('dMy', strtotime($i['voucher_timeslot']->booking)): ""}}</span>
			</p>
			<p>
				<span class="col-md-3"  ></span>
				<span class="col-md-3"  >Period:</span>
				<span class="col-md-5" >{{$i['voucher_timeslot']?date('H:i', strtotime($i['voucher_timeslot']->from)): ""}} - {{$i['voucher_timeslot']?  date('H:i', strtotime($i['voucher_timeslot']->to)) : ""}}</span>
			</p>
			<p>
				<span class="col-md-3" ></span>
				<span class="col-md-3" >Quantity:</span>
				<span class="col-md-5" >{{$i['voucher_timeslot']?$i['voucher_timeslot']->qty_ordered: ""}}person</span>
			</p>
			<p>
				<span class="col-md-3" >Location:</span>
				<span class="col-md-9" style="word-wrap: break-word; font-size: smaller;">
					{{$i['voucher_timeslot']?$i['voucher_address']->line1: ""}}<br>
					{{$i['voucher_timeslot']?$i['voucher_address']->line2: ""}}<br>
					{{$i['voucher_timeslot']?$i['voucher_address']->line3: ""}}<br>
					{{$i['voucher_timeslot']?$i['voucher_address']->line4: ""}}
				</span>
			</p>

			<span style="margin-left: 280px;"><a href="#"><i>Term & Condition</i></a></span>
		</div>
		<div class="col-md-3 col-sm-2" style="background-color: #808080; color:white; min-height: 164px">

			<span style="display: flex;">Discounted Price</span>
			<p style="width: 100%;">
				<span class="pull-right" style="font-size:40px; padding-top: 37px" >
					<span id="percentage">
						{{$discount}}%
					</span>
				</span>
			</p>
			<p style="width: 40%;line-height: 0;">{{$currency->code or "MYR"}}<span class="pull-right">{{number_format($vp_discounted_price/100,2)}}</span></p>
			<p style="width: 40%">Qty<span class="pull-right">{{$i['voucher_timeslot']?$i['voucher_timeslot']->qty_ordered: 1}}</span>

			</p>	
			<p style="width: 40%">
				Total
			</p>			
			<p style="width: 40%">
				{{$currency->code or "MYR"}}
				<span class="pull-right">
					{{number_format($vp_discounted_price*intval($i['voucher_timeslot']?$i['voucher_timeslot']->qty_ordered: 1))}}
				</span>
			</p>			
			<span style=" display: flex;color:black !important;line-height: 15px;">Reference No
				<br> {{$i['voucher']->reference_no}}</span>
		</div>

		<div class="col-md-2" >
			<button class="btn btn-success" style="width: 100%">Approve</button>
			<button class="btn btn-warning" style="width: 100%">Reject</button>
			<label class="label label-info">Status: {{$i['voucher']->status}}</label>
		</div>
	</div>
	@endforeach
</div>