<?php
use App\Http\Controllers\UtilityController;
use App\Classes;
$ii = 1;
?>
<div class="row">
	<div class=" col-sm-12">
		<a class="add_row_crm btn btn-info pull-right" style=" margin-right: 5px; width: 120px;"
							href="javascript:void(0)">+ Customer</a>
	</div>
	<div class="clearfix"></div>
	<br>
	<div class=" col-sm-12">
		<table class="table table-bordered"  id="crm-table" width="98%">
			<thead>
			
			<tr class="bg-black">
				<th class="bsmall">No</th>
				<th class="text-center">Name</th>
				<th class="text-center">Email</th>
			</tr>
			</thead>						
			<tbody>
			@foreach($customers as $customer)
				<tr>
					<td class="text-center">{{$ii}}</td>
					<td class="text-center">
						{{$customer->first_name}}&nbsp;{{$customer->last_name}}
					</td>
					<td class="text-center">											
						{{$customer->email}}	
					</td>
				</tr>
				<?php $ii++; ?>
			@endforeach
			</tbody>
		</table>
	</div>
	<input type="hidden" value="{{$ii}}" id="numecrm" />
</div>
<div class="clearfix"></div>
<script type="text/javascript">
	function newfirstToUpperCase( str ) {
		return str.substr(0, 1).toUpperCase() + str.substr(1);
	}

	function newvalidateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	
    $(document).ready(function(){		
		var camp_table = $('#crm-table').DataTable({
		"order": [],
		 "columns": [
				{ "width": "20px", "orderable": false },
				{ "width": "300px" },
				{ "width": "300px" },
			]
		});		
		$(document).delegate( '.add_row_crm', "click",function (event) {
			var e = parseInt($("#numecrm").val());
			var rowNode = camp_table.row.add( ["<p align='center'>"+ e + "</p>","<p align='center' id='username"+e+"'></p>", "<p align='center' id='useremail"+e+"' style='display: none;'></p><p align='center' id='userkey"+e+"'><input type='text' class='form-control key_customer' placeholder='Enter employee email...' rel='"+e+"' /></p>"] ).draw();
			$( rowNode )
			.css( 'text-align', 'center');
			e++;
			$("#numecrm").val(e);						
	
		});	

		$(document).delegate( '.key_customer', "blur",function (event) {
			var keyemployee = $(this);
			var email = $(this).val();
			var rel = $(this).attr('rel');
			var recruiter = $("#userjust_id").val();
			if(newvalidateEmail(email)){
				$.ajax({
					type: "POST",
					data: {email: email,recruiter: recruiter},
					url: "/buyer/crm/customers/add",
					dataType: 'json',
					success: function (data) {
						console.log(data);
						if(data.status == "warning"){
							toastr.warning(data.long_message);
						}
						if(data.status == "error"){
							toastr.error(data.long_message);
						}
						if(data.status == "success"){
							toastr.info(data.long_message);
							$("#username" + rel).html(data.employee['name']);	
							$("#useremail" + rel).html(data.employee['email']);
							$("#useremail" + rel).show();
							$(".key_customer").hide();
						}
						$(".key_customer").val("");
						
						$("#mailspin").hide();
					},
					error: function (error) {
						$("#mailspin").hide();
						toastr.error("An unexpected error ocurred");
					}

				});				
				
			} else {
				if(email != ""){
					toastr.error("Invalid email! Please, type a valid email.");
				}
			}			
		});	
		$(".dataTables_empty").attr("colspan","100%");
    });
</script>