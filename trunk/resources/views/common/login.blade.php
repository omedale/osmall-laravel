<div class='form-modal '>
    <div class="modal fade" id='loginModal'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>

                    <div class="col-md-12 modal-inside">
                        <form  id="loginFormDeskto" class="login_form" action="{{ URL::to('LoginUser') }}" method="post"
                              data-bv-message="This value is not valid"
                              data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                              data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                              data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
                        <div class="row" style="padding-bottom: 0px !important;">
                            <div class="login-content">
                                <h3 style='font-weight: 900'>Log In</h3>
                                <h5 class="nomobile">For enquiry please fill in the information below</h5><br>

                                <div id="error-msg"
								style="color: #FFD6D6;"
								class="text-center text-danger error-msg">
                                                            
                                </div>
                                <div class="form-group">
                                    <label class='col-md-4' style='padding-left: 0px'>Email:</label>
                                    <div class="col-md-8" style='padding-left: 0px'>
                                        <input class="form-control input-sm" name="username"
											placeholder="abc@yourmail.com" type="text"
											data-bv-trigger="keyup" required
											data-bv-notempty-message="Email is required is required" >

                                    </div>
                                </div>
                              <!--  <div class="height-gap"></div> -->
                                <div class="form-group">
                                    <label class='col-md-4'  style='padding-left: 0px'>Password:</label>
                                    <div class="col-md-8" style='padding-left: 0px'>
                                        <input type='password' name="password"
											class="form-control input-sm"  placeholder="Type your Password"
											data-bv-trigger="keyup" required
											data-bv-notempty-message="Password is required">
                                     </div>
                                    <div class="col-md-8 col-sm-offset-4" style='padding-left: 0px'>
                                        <a href="#" style='color:#fff; text-decoration: underline' data-toggle="modal" data-target="#forgotModal">Forgot your password or username?</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top: 0px !important;">
                            <div class="m-footer">
                                <div class="col-xs-6" style='text-align: left; padding-left: 0px'>
									<h5>
                                         <a style="color:#fff;" href="{{ route('oauth.login', ['facebook']) }}" id="facebooklogin"> <u>Sign in with</u> &nbsp;<span>
                                                <img alt="" src="{{asset('images/fb.png')}}" style='width:40px; height:40px;'>
                                            </span> </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="color:#fff;" href="{{ url('create_new_buyer') }}"><u>Sign Up</u></a>
                                    </h5>
                                </div>
                                <div class="col-xs-6">
                                    <button type="submit" class='btn signInBtn'>Sign In</button>
                                </div>
                            </div>
							
                        </div> 
						<!-- Autolink Message -->
						<p style="color: #FFF; display: none; font-size: 15px;" id="modal_autolink_message"><b>Use AutoLink to request a link to our merchant to be a distributor or dealer. Dealership or distributorship request has to be approved by the merchant first.</b></p>						
						<p style="color: #FFF; display: none; font-size: 15px;" id="modal_smm_message"><b>Social Media Marketeer is a wonderful way to earn extra cash by helping merchants to market their products via your social media network. Register and try it out!</b></p>						
                            </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class='form-modal'>
    <div class="modal fade" id='forgotModal'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>

                    <div class="col-md-12 modal-inside">
                        <div class="row">
                            <div class="login-content">
                                <h3 style='font-weight: 900'>Support</h3>

                                <h5>Forgot your password?</h5><br>

                                <div class="form-group">
                                    <label class='col-md-4' style='padding-left: 0px'>Provide your email:</label>
                                    <div class="col-md-8" style='padding-left: 0px'>
                                        <span style="position: relative; color: black; display:none; font-size: 24px; font-weight: bold;" class="all-filter-fa" id="overlay_spinner_email_forgot" ><i class="fa-li fa fa-spinner fa-spin fa fa-fw" ></i></span>
										<input class="form-control input-sm" name="username" id="forgotemail" placeholder="Type your email" type="text"
                                               data-bv-trigger="keyup" required data-bv-notempty-message="Username is required">
										<label id="email-forgot-error" class="error" for="email" style="display:none">Invalid Email</label>
										<label id="email-forgot-check" style="display:none; color: #FFF;">Please, check your email for further instruction to recover your password.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style='text-align: right; padding-left: 0px'>
                                <button class='btn  signInBtn forgotpassBtn' id="forgot_email">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class='form-modal'>
    <div class="modal fade" id='editModel'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="background:#fff !important;" id="editbody">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class='form-modal'>
    <div class="modal fade" id='AlertModel'>
        <div class="modal-dialog">
            <div class="modal-content" style="background:#fff !important;">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Alert!</strong></h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="yes-del" data-dismiss="modal">Yes</button>
                    <button type="button" class="btn btn-primary" id="no-del" data-dismiss="modal">No</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<div class='form-modal'>
    <div class="modal fade" id='MessageModel'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="background:#fff !important;" id="Messagebody">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<script>
 $(document).ready(function () {
	function validateEmail2(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}	 
	 
	$("#forgotemail").on("blur",function(){
		//Put Spinner
		$("#overlay_spinner_email_forgot").css("display", "block");
		$("#email-forgot-error").css("display", "none");
		$("#forgotemail").removeClass("error");

		//JS Email Valitation (Required and Well Format)
		var email_v = $("#forgotemail").val();
		if (validateEmail2(email_v)) {
			// $("#email-error").text(email_v + " is valid :)");
			// $("#email-error").css("color", "green");
			// $("#email-error").css("display", "block");
			$.ajax({
				type: "get",
				url: JS_BASE_URL + '/validate_email/' + email_v,
				cache: false,
				success: function (responseData, textStatus, jqXHR) {
					if (responseData != "0") {
						$("#email-forgot-error").text("This email could not be found");
						$("#email-forgot-error").css("color", "red");
						$("#forgotemail").addClass("error");
						$("#email-forgot-error").css("display", "block");
					}
					 $("#overlay_spinner_email_forgot").css("display", "none");
				},
				error: function (responseData, textStatus, errorThrown) {
					console.log(errorThrown);
				}
			});

		} else {
			$("#email-forgot-error").text("Invalid format email");
			$("#email-forgot-error").css("color", "red");
			$("#forgotemail").addClass("error");
			$("#email-forgot-error").css("display", "block");
			$("#overlay_spinner_email_forgot").css("display", "none");
		}
	});
	$("#forgot_email").on("click",function(){
		var email = $("#forgotemail").val();
		var formData = {
			email: email
		}
		$.ajax({
			type: "post",
			url: JS_BASE_URL + '/forgot_password',
			cache: false,
			data: formData,
			dataType: 'json',
			success: function (data) {
				console.log(data);
				$("#email-forgot-check").css("display", "block");
				setTimeout(function(){
					$("#email-forgot-check").css("display", "none");
					$('#forgotModal').modal('toggle');
				}, 5000);					
			},
			error: function (responseData, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});		
		
	});
	
	$("#forgotemail2").on("blur",function(){
		//Put Spinner
		$("#overlay_spinner_email_forgot2").css("display", "block");
		$("#email-forgot-error2").css("display", "none");
		$("#forgotemail2").removeClass("error");

		//JS Email Valitation (Required and Well Format)
		var email_v = $("#forgotemail2").val();
		if (validateEmail2(email_v)) {
			// $("#email-error").text(email_v + " is valid :)");
			// $("#email-error").css("color", "green");
			// $("#email-error").css("display", "block");
			$.ajax({
				type: "get",
				url: JS_BASE_URL + '/validate_email/' + email_v,
				cache: false,
				success: function (responseData, textStatus, jqXHR) {
					if (responseData != "0") {
						$("#email-forgot-error2").text("This email could not be found");
						$("#email-forgot-error2").css("color", "red");
						$("#forgotemail2").addClass("error");
						$("#email-forgot-error2").css("display", "block");
					}
					 $("#overlay_spinner_email_forgot2").css("display", "none");
				},
				error: function (responseData, textStatus, errorThrown) {
					console.log(errorThrown);
				}
			});

		} else {
			$("#email-forgot-error2").text("Invalid format email");
			$("#email-forgot-error2").css("color", "red");
			$("#forgotemail2").addClass("error");
			$("#email-forgot-error2").css("display", "block");
			$("#overlay_spinner_email_forgot2").css("display", "none");
		}
	});
	$("#forgot_email2").on("click",function(){
		var email = $("#forgotemail2").val();
		var formData = {
			email: email
		}
		$(this).html("Sending...");
		var _this = $(this);
		$.ajax({
			type: "post",
			url: JS_BASE_URL + '/forgot_password',
			cache: false,
			data: formData,
			dataType: 'json',
			success: function (data) {
				console.log(data);
				$("#email-forgot-check2").css("display", "block");
				_this.html("Send");
				setTimeout(function(){
					$("#email-forgot-check2").css("display", "none");
					$('.dropdown-content-forgot').hide();
				}, 5000);					
			},
			error: function (responseData, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});		
		
	});	
	
});			
</script>
