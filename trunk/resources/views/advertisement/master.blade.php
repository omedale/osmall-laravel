@extends("common.default")

@section("content")
<div class="container" style="margin-top:30px;">
	@include('admin/panelHeading')	
	<div class="clearfix"></div>
	<div class="col-sm-6 no-padding">
		<h2>Master: Advertisement</h2>
	</div>
	<div class="col-sm-6">
		<h3 class="pull-right">Total: MYR <span id="totaladp" ></span></h3>
	</div>
	<div class="clearfix"></div>
	<div class="table-responsive">
		<table class="table table-bordered" cellspacing="0" width="100%" id="gridnetwork">
			<thead style="background-color: #188BFD; color: white;">
				<tr>
					<th class="no-sort text-center bsmall">No</th>
					<th class="text-center medium">Advertisement&nbsp;ID</th>
					<th class="text-center medium">Section</th>
					<th class="text-center medium">Placement</th>
					<th class="text-center medium">Booking</th>
					<th class="text-center medium">Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$m = 0;
					$totalad = 0;
				?>
				@foreach($advertisements as $advertisement)
				<?php $totalad+=$advertisement->price; ?>
				<tr>
					<td class="text-center">
						{{ $m }}
					</td>		
					<td class="text-center">{{STR_PAD($advertisement->id,10,'0',STR_PAD_LEFT)}}</td>					
					<td class="text-center">{{$advertisement->target}}</td>					
					<td class="text-center">{{$advertisement->placement}}</td>					
					<td class="text-center"></td>					
					<td class="text-center">MYR&nbsp;{{number_format($advertisement->price/100,2,'.',',')}}</td>																	
				</tr>
				@endforeach
			</tbody>
		</table>
		<input type="hidden" value="{{number_format($totalad/100,2,'.',',')}}" id="totalad" />
	</div>	
</div>
<meta name="_token" content="{!! csrf_token() !!}"/>
<script type="text/javascript">
$(document).ready(function () {
	$("#totaladp").html($("#totalad").val());
    $('#gridnetwork').DataTable({
        "order": [],
        "scrollX": true,
        "scrollY": false,
        "columnDefs": [
			{"targets": 'no-sort', "orderable": false, },
			{"targets": "medium", "width": "100px" },
			{"targets": "large",  "width": "120px" },
		//	{"targets": "approv", "width": "180px"},
			{"targets": "blarge", "width": "200px"},
			{"targets": "bsmall",  "width": "20px"},
			{"targets": "clarge", "width": "250px"},
			{"targets": "xlarge", "width": "300px" }
		],
        "fixedColumns":  true
    });

 });
</script>	
@stop
