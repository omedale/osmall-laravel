<?php


$cf = new \App\lib\CommonFunction();
use App\Http\Controllers\UtilityController;
use App\Models\AdControl;
use App\Models\AdTarget;
use App\Models\AdImage;
use Illuminate\Support\Facades\Route;

UtilityController::forget_session();

?>

<header style="background-color: black">
<style type="text/css">
	
a, a:visited, a:focus, a:active, a:hover{
    outline:0 none !important;
}

.nohover {
	background-color: transparent;
}
 
.navClor li a{color: white;}

.nav>li>a{
	margin-left: -0.87em;
	/*display:inline-block;*/
}

.mega-dropdown {
  position: static !important;
  /*position: absolute;*/
  list-style: none;
  /*left: auto;*/
  margin-right: auto;
  
}
.mega-dropdown-menu {
    padding: 20px 0px;
    font-size: 0.7em;
    width: 100%;
    -webkit-box-shadow: -7px 9px 6px 1px rgba(0,0,0,0.75);
	-moz-box-shadow: -7px 9px 6px 1px rgba(0,0,0,0.75);
	box-shadow: -7px 9px 6px 1px rgba(0,0,0,0.75);
    z-index: 99999;
    left: auto;
}
.mega-dropdown-menu > li > ul {
  padding: 0;
  margin: 0;
  list-style: none;
}
.mega-dropdown-menu > li > ul > li {
  list-style: none;
}
.mega-dropdown-menu > li > ul > li > a {
  display: block;
  padding: 3px 5px;
}
.mega-dropdown-menu > li ul > li > a:hover,
.mega-dropdown-menu > li ul > li > a:focus {
  text-decoration: none;
}
.mega-dropdown-menu .dropdown-header {
  font-size: 18px;
  color: #ff3546;
  padding: 5px 60px 5px 5px;
  line-height: 30px;
}
.masonry-megamenu {
    display: none;
    z-index: 99999;
    position: absolute !important;
    width: 100%;
    background: #fff;
    padding-bottom: 15px;
    -moz-border-radius-bottomright: 6px;
    -webkit-border-bottom-right-radius: 6px;
    border-bottom-right-radius: 6px;
    -moz-border-radius-bottomleft: 6px;
    -webkit-border-bottom-left-radius: 6px;
    border-bottom-left-radius: 5px;
    -webkit-box-shadow: 0px 5px 11px 0px rgba(50, 50, 50, 0.34);
    -moz-box-shadow:    0px 5px 11px 0px rgba(50, 50, 50, 0.34);
    box-shadow:         0px 5px 11px 0px rgba(50, 50, 50, 0.34);
}
.nav-submenu {
    margin-left: 30px;
}
.nav-submenu li a {
    padding: 3px 5px;
}
.brand-tabs {
    font-weight: bold;
    font-size: 12px;
}
#headerBrand {
    height: auto !important;
    min-height: 250px;
    height: 250px; /* Old IE fallback */
}

/***************** MOBILE OPTIMIZATION *********************/
.menu-toggle {
    position: relative;
    padding: 9px 10px;
    margin-top: 8px;
    margin-right: 15px;
    margin-bottom: 8px;
    background-color: transparent;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}

.btn-facebook{color:#fff;border-color:rgba(0,0,0,0.2)}.btn-facebook:hover,.btn-facebook:focus,.btn-facebook:active,.btn-facebook.active,.open .dropdown-toggle.btn-facebook{color:#fff;background-color:#30487b;border-color:rgba(0,0,0,0.2)}
.btn-facebook:active,.btn-facebook.active,.open .dropdown-toggle.btn-facebook{background-image:none}
.btn-facebook.disabled,.btn-facebook[disabled],fieldset[disabled] .btn-facebook,.btn-facebook.disabled:hover,.btn-facebook[disabled]:hover,fieldset[disabled] .btn-facebook:hover,.btn-facebook.disabled:focus,.btn-facebook[disabled]:focus,fieldset[disabled] .btn-facebook:focus,.btn-facebook.disabled:active,.btn-facebook[disabled]:active,fieldset[disabled] .btn-facebook:active,.btn-facebook.disabled.active,.btn-facebook[disabled].active,fieldset[disabled] .btn-facebook.active{background-color:#3b5998;border-color:rgba(0,0,0,0.2)}
.btn-social{position:relative;padding-left:44px;text-align:left;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.btn-social :first-child{position:absolute;left:0;top:0;bottom:0;width:32px;line-height:34px;font-size:1.6em;text-align:center;border-right:1px solid rgba(0,0,0,0.2)}
.btn-social.btn-lg{padding-left:61px}.btn-social.btn-lg :first-child{line-height:45px;width:45px;font-size:1.8em}
.btn-social.btn-sm{padding-left:38px}.btn-social.btn-sm :first-child{line-height:28px;width:28px;font-size:1.4em}
.btn-social.btn-xs{padding-left:30px}.btn-social.btn-xs :first-child{line-height:20px;width:20px;font-size:1.2em}
.btn-social-icon{position:relative;padding-left:44px;text-align:left;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;height:34px;width:34px;padding-left:0;padding-right:0}.btn-social-icon :first-child{position:absolute;left:0;top:0;bottom:0;width:32px;line-height:34px;font-size:1.6em;text-align:center;border-right:1px solid rgba(0,0,0,0.2)}
.btn-social-icon.btn-lg{padding-left:61px}.btn-social-icon.btn-lg :first-child{line-height:45px;width:45px;font-size:1.8em}
.btn-social-icon.btn-sm{padding-left:38px}.btn-social-icon.btn-sm :first-child{line-height:28px;width:28px;font-size:1.4em}
.btn-social-icon.btn-xs{padding-left:30px}.btn-social-icon.btn-xs :first-child{line-height:20px;width:20px;font-size:1.2em}
.btn-social-icon :first-child{border:none;text-align:center;width:100% !important}
.btn-social-icon.btn-lg{height:45px;width:45px;padding-left:0;padding-right:0}
.btn-social-icon.btn-sm{height:30px;width:30px;padding-left:0;padding-right:0}
.btn-social-icon.btn-xs{height:22px;width:22px;padding-left:0;padding-right:0}

@media only screen and (max-width: 600px) {
		/* For mobile phones: */

		.cate-img{
			width: 0px !important;
		}
		
		.col-xs-offset-1 {
			margin-left: 6% !important;
		}
		.specialbox{
			display: none;
		}
		.boxrow4 img {
			object-fit: contain;
			height: 125px;
			width: 100px;
			border: solid 1px lightgrey;
		}
		.boxrow4-l img {
			object-fit: contain;
			height: 300px;
			border: solid 1px lightgrey;
		}
		.badge-cutoff {
			background: rgb(255, 0, 128) none repeat scroll 0 0;
			border-radius: 50%;
			color: #fff;
			font-size: 12px;
			height: 30px;
			line-height: 10px;
			padding: 5px 0;
			position: absolute;
			right: 20px;
			text-align: center;
			top: 10px;
			width: 30px;
		}	
	
	header{
		height: 55px;
	}
	
	.slidesjs-container{
		height: 300px !important;
		width: 100% !important;
	}
	
	.slidesjs-control{
		height: 300px !important;
		width: 100% !important;
	}
	
    .top-header{
        display: none;
    }
    .logo-header{
        display: none;
    }
    .omenu{
        display: none;
    }
    .ominimenu{
        display: none;
    }
    .nomobile{
        display: none;
    }
    .container {
        width: 100% !important;
        /*background-color: #D9D9D9;*/
    }
    .container-fluid {
        /*background-color: #D9D9D9;*/
    }
    .dropdown {
    position: relative;
    display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        padding: 12px 16px;
        z-index: 20;
        margin-top: 5px;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
    .dropdown-content_a {
        color: black;
    }

    .modal-body{
        margin-left: 0px !important;
        margin-right: 0px !important;
        padding: 0px !important;
    }
    
    .modal-dialog {
        margin-top: 5px !important;
        margin-right: 10px !important;
        margin-bottom: 5px !important;
        margin-left: 2px !important;
    }
    
    .navbar-form{
        padding: 0 !important;
        margin-right: 0px !important;
    }
}
@media only screen and (min-width: 600px) {
    .mobile-header{
        display: none;
    }
    .mobile{
        display: none;
    }

}


.dropdown-content-menu {
    display: none;
    position: absolute;
    background-color: rgba(0,0,0,0.8);
    width: 99%;
	margin-top: -2px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 5;
}

.dropdown-content-login {
    display: none;
    position: absolute;
    background-color: rgba(0,0,0,1);
    width: 100%;
    height: 100%;
	margin-top: -2px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 6;
}

.dropdown-content-forgot {
    display: none;
    position: absolute;
    background-color: rgba(0,0,0,1);
    width: 100%;
    height: 100%;
	margin-top: -2px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 7;
}

.dropdown-content-systembuyer {
    display: none;
    position: absolute;
    background-color: rgba(0,0,0,1);
    width: 100%;
    height: 100%;
	margin-top: -2px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 6;
}

.dropdown-content-systemseller {
    display: none;
    position: absolute;
    background-color: rgba(0,0,0,1);
    width: 100%;
    height: 100%;
	margin-top: -2px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 6;
}

.dropdown-content-systemadmin {
    display: none;
    position: absolute;
    background-color: rgba(0,0,0,1);
    width: 100%;
    height: 100%;
	margin-top: -2px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 6;
}

.mobilemenuhr{
	margin: 0 !important;
	border-color: #73D2C6 !important;
	width: 90% !important;
	padding: 5px;
}

.badgesmobile {
    background-color: #f14141;
    padding: 3px 6px;
    text-align: center;
    z-index: 0;
    font-weight: normal;
    font-size: 11px;
    right: 0;
    top: -1px;
    bottom: auto;
    vertical-align: text-top;
    border-radius: 5px;
}

.omenu .nav li a:focus{background: #1abc9c; cursor: pointer;}

.nohover:hover{background: #000000 !important; cursor: auto;}
</style>
    <!--Begin Logo Section -->
    <div class="mobile-header" style="font-size:13px !important;" style="background-color: black; ">
        <div class="mobileheadermain">
			<div class="col-xs-1 no-padding">
				<div class="dropdown">
					<button type="button"
						style="border: solid 0px #fff; color: #fff;  margin-right: 0px !important;"
						class="menu-toggle" id="first-menu">
						<img src="{{asset('images/category/menu-white.png')}}"
						width="22px">
					</button>
				</div>
			</div>
			<?php 
				$ocredit=0;
				if (Auth::check()) {
					# code...
					
				   // $oacc= DB::table('ocredit_acct')->where('user_id',Auth::user()->id)->first();
					$oacc=UtilityController::ocredit()['ocredit'];
					if (is_null($oacc)) {
						# code...
						$ocredit=0;
					} else{
						$ocredit=$oacc/100;
					}
				} else {
					
				}
			   ?>
			<div class="col-xs-5">
				 <center><a href="{{route('home')}}">
				 <img src="{{asset('images/logo-white.png')}}"
					width="120%"
					style="margin-top:10px;margin-bottom:10px;padding-right:2px"
					alt="Logo">
					</a>
				</center>
			</div>
			<div class="col-xs-5 no-padding"
			style="background-color:black;color:#FFF;height:100%;left:10px">   
				@if(Auth::check())
					<p style="font-size:13px !important;margin-top:20px !important;"><strong
					style="color:#73D2C6;font-size:1em;margin-left:10px">
					{{number_format($ocredit,2)}} Pts</strong></p>
				@endif
			</div>
			<div class="col-xs-1 no-padding">
			   <span class="glyphicon glyphicon-search glyphsearch"
			   style="color:white; font-size: 16px; margin-top: 20px !important;" ></span>
			</div>
        </div>
		<div class="col-xs-11 searchingmenu" style="display: none;">
			{!! Form::open(array('route' => 'search', 'class'=>'navbar-form navbar-right')) !!}
				<div class="input-group input-group-sm pull-right"
					>
					{!! Form::text('search_key_word', null,array(
						'class'=>'form-control','placeholder'=>'Search')) !!}
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default">
						<span class="glyphicon glyphicon-search"></span>
						</button>
					</span>
				</div><!-- /input-group -->
			{!! Form::close() !!}			
		</div>
		<div class="col-xs-1 no-padding searchingmenu" style="display: none;">
			<span class="glyphicon glyphicon-remove glyphsearchclose"
				style="color:white; font-size: 16px; margin-top: 15px !important;">
			</span>
		</div>
		<div class="clearfix"></div>
		<div class="dropdown-content-systemadmin">
			<span class="pull-right closesystemmenuadmin"
					style="padding-right: 10px; cursor: pointer;color:#73D2C6  !important; font-size: 18px;">X</span>	
			<h3 style='font-weight: 900; color: #73D2C6 !important;' align='center'>System Menu</h3>
			<br>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Admin</a></h4>
		</div>		
		<div class="clearfix"></div>
		<div class="dropdown-content-systemseller">
			<span class="pull-right closesystemmenuseller"
					style="padding-right: 10px; cursor: pointer;color:#73D2C6  !important; font-size: 18px;">X</span>	
			<h3 style='font-weight: 900; color: #73D2C6 !important;' align='center'>System Menu</h3>
			<?php 
				$mcolor = "#FFF;";
				$scolor = "#FFF;";
				if(Auth::check()){
					if(Auth::user()->hasRole('mer') || Auth::user()->hasRole('hcu') || Auth::user()->hasRole('fmu')){
						$mcolor = "#73D2C6;";
					}
					if(Auth::user()->hasRole('sto')){
						$scolor = "#73D2C6;";
					}
				}
			?>
			<div class="row" style="margin-right: 5px !important; margin-left: 5px !important;">
				<div class="col-xs-6" style="border: 1px solid #73D2C6;"><h3 align="center" style="color: {{$mcolor}}">Merchant</h3></div>
				<div class="col-xs-6" style="border: 1px solid #73D2C6;"><h3 align="center" style="color: {{$scolor}}">Station</h3></div>
			</div>
			<br>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Account&nbsp;Information</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Sales&nbsp;Orders</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Member&nbsp;List</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Likes</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">OpenWish</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">SMM</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Documents</a></h4>
			<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Sales&nbsp;Report</a></h4>
		</div>
        <div class="clearfix"></div>
		<div class="dropdown-content-systembuyer">
			<span class="pull-right closesystemmenu"
					style="padding-right: 10px; cursor: pointer;color:#73D2C6  !important; font-size: 18px;">X</span>	
			<h3 style='font-weight: 900; color: #73D2C6 !important;' align='center'>System Menu</h3>
			<br>
			@if(Auth::check())
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Shopping</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/mobinformation/{{Auth::user()->id}}">Buyer&nbsp;Information</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{url("buyer/dashboard")."#orders"}}">Orders</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/mobautolink/{{Auth::user()->id}}">Autolink</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/mobdocuments/{{Auth::user()->id}}">Documents</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Performance</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/moblikes/{{Auth::user()->id}}">Likes</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/mobdiscount/{{Auth::user()->id}}">Discount</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{url("buyer/dashboard")."#dopenwish"}}">OpenWish</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{url("buyer/dashboard")."#smm"}}">SMM</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/mobhyper/{{Auth::user()->id}}">Hyper</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="{{URL::to('/')}}/buyer/mobstaff/{{Auth::user()->id}}">Staff</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Pi-Shop</a></h4>
				<h4 style="color: #73D2C6 !important; margin-left: 10px;"><a style="color: #73D2C6 !important;" href="#">Whiteboard</a></h4>
			@endif
		</div>
        <div class="clearfix"></div>
		<div class="dropdown-content-forgot">
				<span class="pull-right closemobileforgot"
					style="padding-right: 10px; cursor: pointer;color:#34dabb  !important; font-size: 18px;">X</span>				
				<h3 style='font-weight: 900; color: #000000 !important;'>Forgot Password</h3>
				<ol style="visibility:hidden;">
					<li>&nbsp;</li>
				</ol>	
				<div class="row">
					<div class="col-xs-1"></div>
					<div class="col-xs-10">
						<input class="form-control input-sm" name="username" id="forgotemail2" placeholder="Type your email" type="text"
							   data-bv-trigger="keyup" required data-bv-notempty-message="Username is required">
						<label id="email-forgot-error2" class="error" for="email" style="display:none">Invalid Email</label>
						<label id="email-forgot-check2" style="display:none; color: #FFF;">Please, check your email for further instruction to recover your password.</label>
					</div>
					<div class="col-xs-1"></div>
				</div>	
				<div class="row">
					<div class="col-xs-11" style='text-align: right; padding-left: 0px'>
						<a href="#" style="color: #73D2C6;" id="forgot_email2">Send</a>
					</div>
				</div>				
		</div>
		<div class="dropdown-content-login">
			
			<div class="mobileloginbody" style="padding: 5px;">
				<span class="pull-right closemobilelogin"
					style="padding-right: 10px; cursor: pointer;color:#34dabb  !important; font-size: 18px;">X</span>				
				<h3 style='font-weight: 900; color: #000000 !important;'>Log In</h3>
				<ol style="visibility:hidden;">
						<li>&nbsp;</li>
					</ol>
				<form  id="loginForm2" class="mobile_login_form" action="{{ URL::to('LoginUser') }}" method="post"
                              data-bv-message="This value is not valid"
                              data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                              data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                              data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
					<div id="error-msg"
					style="color: #FFD6D6;"
					class="text-center text-danger"></div>
					<div class="row">
						<div class="col-xs-1"></div>
						<div class="col-xs-10">
							<input class="form-control input-sm" name="username"
								placeholder="abc@yourmail.com" type="text"
								data-bv-trigger="keyup" required
								data-bv-notempty-message="Email is required is required" >
						</div>
						<div class="col-xs-1"></div>
					</div>
					<ol style="visibility:hidden;">
						<li>&nbsp;</li>
					</ol>
					<div class="row">
						<div class="col-xs-1"></div>
						<div class="col-xs-10">
							<input type='password' name="password"
								class="form-control input-sm"
									placeholder="Type your Password"
								data-bv-trigger="keyup" required
								data-bv-notempty-message="Password is required">
						</div>
						<div class="col-xs-1"></div>
					</div>
					<ol style="visibility:hidden;">
						<li>&nbsp;</li>
					</ol>
					<div class="row"
						style="color:#34dabb !important; font-size: 1em; font-weight: regular;">
						<div class="col-xs-1">
						</div>
						<div class="col-xs-3">
						<a href="{{ url('create_new_buyer') }}"
						style="color: inherit;">Sign&nbsp;Up&nbsp;&nbsp;|
						</a></div>
						{{-- <div class="col-xs-1">|</div> --}}
						<div class="col-xs-2" >
						<a href="#" style="color: inherit;"
						id="signin_on_mobile">
						Log&nbsp;In&nbsp;&nbsp;|</a></div>
						
						<div class="col-xs-4 pull-left">
						<a href="javascript:void(0);"
						class="forgot_password"
						style="color: inherit;">
						Forgot&nbsp;Password</a></div>
						<div class="col-xs-1"></div>

					</div>
							
				</form>
				<div class="row" style="margin-top:20px">
					<div class="col-xs-1"></div>
					<div class="col-xs-10 social-buttons">
						<a class="btn btn-block btn-social btn-facebook"
							href="{{url('auth/facebook/login')}}">
							<i class="fa fa-facebook"></i>
							Sign in with Facebook
						  </a>
					</div>
					<div class="col-xs-1"></div>
				</div>
			
			</div>
		</div>
		<div class="dropdown-content-menu">
			<div class="col-xs-2 no-padding"
				style="padding: 2px !important; text-center">
				<?php 
					$systemmenuclass = "";		
				?>
				@if(!Auth::check())
					<img src="{{asset('images/category/small_logo_gray.png')}}"
					style="margin-top:4px;margin-bottom:3px;margin-left:9px"
					width="70%" alt="Logo">
				@else
					<?php 
						if(Auth::user()->hasRole('byr')){
							$systemmenuclass = "systemmenu";	
						} else if(Auth::user()->hasRole('mer') || Auth::user()->hasRole('sto') || Auth::user()->hasRole('hcu') || Auth::user()->hasRole('fmu')){
							$systemmenuclass = "systemmenuseller";
						} else if(Auth::user()->hasRole('adm')){
							$systemmenuclass = "systemmenuadmin";
						}
					?>
					<img src="{{asset('images/category/small_logo.png')}}"
					style="margin-top:4px;margin-bottom:3px;margin-left:9px"
					class="{{$systemmenuclass}}"
					width="70%" alt="Logo">
				@endif
				@if(!Auth::check())
					<p style="font-size:11px !important; "
					align="center">
					<strong
					style="color:#FFF;font-size:1em">
					System</strong></p>
				@else
					<p style="font-size:11px !important;"
					align="center"
					class="{{$systemmenuclass}}">
					<strong
					style="color:#FFF;font-size:1em">
					System</strong></p>
				@endif
			</div>
			<div class="col-xs-10 no-padding"
			style="margin-top:5px">
				@if(!Auth::check())
					<a href="{{ url('create_new_buyer') }}" style="color: #73D2C6 !important; font-size: 16px;">Sign Up&nbsp;</a><span style="color: #73D2C6 !important; font-size: 16px;"><b>|</b>&nbsp;</span><a href="javascript:void(0);" class="loginmobile" style="color: #73D2C6 !important; font-size: 16px;">Log In</a>&nbsp;<span style="color: #73D2C6 !important; font-size: 16px;"><b>|</b>&nbsp;</span><a href="javascript:void(0);" class="forgot_password" style="color: #73D2C6 !important; font-size: 16px;">Forgot Password</a>
				@else
					<a href="{{URL::to('auth/logout')}}" style="color: #73D2C6 !important; font-size: 16px;" id="loginout2">Logout</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				@endif
				<span class="pull-right closemobilemenu" style="padding-right: 10px; cursor: pointer; color: #73D2C6 !important; font-size: 18px;">X</span>
				@if(Cart::totalItems() < 1)
				  <div>
					  <a href="{{URL::to('cart')}}" class=''
					  style="margin-left:0px;padding-left:0px;padding-right:0px; color: #34dabb;padding-right:0; float: left;"> 
					  <i class="fa fa-shopping-cart"
					  style="font-size: 35px;height: 20px;">
					  <span class="badgesmobile"
					  style="opacity: 1; display: none; z-index: 4; color: white;font-size: 14px;">
					  </span></i>&nbsp;&nbsp;&nbsp;&nbsp;
					  
					  </a>
					  <span style="font-size: 15px; color: #73D2C6;"
					  class="cart-link">Cart is Empty</span>  
					  @if(Auth::check())	
						<p style="font-size:13px !important;  margin-top: 0px !important; padding-left:0px;">
						<strong style="color:#FFF;font-size:1em;margin-right:0px;">
						OpenCredit {{number_format($ocredit,2)}} Pts</strong></p>
					  @endif
				  </div>
				@else
				  <a href="{{URL::to('cart')}}" class=''
				  style="margin-left:0px;padding-left:0px;padding-right:0px; color: #34dabb;padding-right:0; float: left;"> <i class="fa fa-shopping-cart" style="font-size: 35px;height: 20px;">
				  <span class="badgesmobile"
				  style="opacity: 1;color: white;font-size: 14px;">
				  {!! Cart::totalItems() !!}
				  </span></i> </a>
				  <span style="font-size: 15px; color: #73D2C6;"
				  class="cart-link">View Cart</span>
				  @if(Auth::check())	
					<p style="font-size:13px !important;  margin-top: 0px !important; padding-left:0px;">
					<strong style="color:#FFF;font-size:1em;margin-right:0px;">
					OpenCredit {{number_format($ocredit,2)}} Pts</strong></p>
				  @endif				  
				@endif
			</div>
			 <div class="clearfix"></div>
			<div class="mobilebotmenu2" style="padding: 5px 5px 0px 5px;">
				<hr class="mobilemenuhr">
			</div>
			<div class="mobilecategories" style="padding: 0px 5px 5px 5px;">
				<h3 style="font-weight:900;color: #73D2C6 !important; margin-top: 0px !important;">Category</h3>
				<div class="mobilesubcategories">
				</div>
			</div>
			<div class="mobilebotmenu" style="padding: 5px;">
				<hr class="mobilemenuhr">
				<a href="javascript:void(0);" style="color: #FFF !important;" class="about_us_show_as_modal"><h4>About Us</h4></a>
				<a href="{{URL::to('/')}}/job" style="color: #FFF !important;"><h4>Job</h4></a>
				<a href="{{URL::to('/')}}/terms_cond" style="color: #FFF !important;"><h4>Terms & Conditions</h4></a>
				<a href="{{URL::to('/')}}/privacy" style="color: #FFF !important;"><h4>Privacy Policy</h4></a>
				<a href="javascript:void(0);" rel-href="{{ route('mobbuyerreg') }}" rel-id="buyerlink" class="prevent" style="color: #FFF !important;"><h4>Buyer Registration</h4></a>
				<a href="javascript:void(0);" rel-href="{{url('create_new_merchant')}}" rel-id="merchantlink" class="prevent"style="color: #FFF !important;"><h4>Merchant Registration</h4></a>
				<a href="javascript:void(0);" rel-href="{{ route('create-station') }}" rel-id="stationlink" class="prevent" style="color: #FFF !important;"><h4>Station Registration</h4></a>
				<a href="{{URL::to('/')}}/downloads" style="color: #FFF !important;"><h4>Download Apps</h4></a>
				<a href="{{URL::to('/')}}/newsletter" style="color: #FFF !important;"><h4>News</h4></a>
			</div>
		</div>
    </div>
   
    <div class="logo-header container" style="margin-top:-10px;">
        <div class="">
        <div class="">
            <div class="row">
                <div class="col-sm-4  logo-holder">
                    <a href="{{route('home')}}" style="">
					<img src="{{asset('images/logo-white.png')}}"
						alt="Logo" width="400px"
						style="">
					</a>
					<!--
					[{{session_id()}}]
					-->
                </div>
                <div class="col-sm-1">&nbsp;</div>
                <div class="col-sm-7 " style="color: white">
				 <ul class="nav navbar-nav navbar-right navClor"
				 style="margin-right: 10px;">
				  
					@if(Auth::check() and Auth::user()->hasRole('byr'))
						<li class="pull-right">
						<a href="{{url('buyer/dashboard')}}">Dashboard</a></li>
					@endif

					@if(!\Illuminate\Support\Facades\Auth::check())
						<li class="pull-right">
							<a href="{{url('create_new_buyer')}}">SignUp</a>
						</li>
					@endif
					<li class="pull-right">
						@if(Auth::check())
							<input type='hidden' value='1' id='loginConfirm'>
							<a href="{{URL::to('auth/logout')}}"
								id="loginout" style="">Logout</a>
						@else
							<a href="#" data-toggle="modal"
								data-target="#loginModal">Login</a>
						@endif
					</li>
					@if(isset($redirect_to_login))
						{{-- marker --}}
						<script type="text/javascript">
							$(document).ready(function(){

								$('#loginModal').modal('show');
							});
						</script>
					@endif
					 @if(Auth::check())
					 @else
					<li class="pull-right">
						<a href="#" data-toggle="modal"
						   data-target="#forgotModal">Forgot Password</a>
					</li>
					@endif
					<li class="pull-right">
						@if(Auth::check())
							<?php
								$roles = Auth::user()->roles;
								$rstring = "";
								$rlen = count($roles);
								$c = 1;
								foreach($roles as $role) {
									if ($c < $rlen) {
										$rstring = $rstring .
											$role->description . ", ";
									} else {
										$rstring = $rstring .
											$role->description;
									}
									$c++;
								}
								$roles = "[".$rstring."]";
							?>


							<a class="nohover">
								{{--<!--
								Welcome, {{Auth::user()->name == null ?
								Auth::user()->first_name ." ".
								Auth::user()->last_name." ".$roles:
								Auth::user()->name." ".$roles}}
								-->--}}
								Welcome, {{Auth::user()->name == null ?
								Auth::user()->first_name ." ".
								Auth::user()->last_name:
								Auth::user()->name}} 
							</a>
						@endif
					</li>
					<div class="clearfix"></div>
					 {!! Form::open(array('route' => 'search', 'class'=>'navbar-form navbar-right')) !!}
					<div class="input-group input-group-sm pull-right"
						>
						{!! Form::text('search_key_word', null,array(
							'class'=>'form-control','placeholder'=>'Search')) !!}
						<span class="input-group-btn">
							<button type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-search"></span>
							</button>
						</span>
					</div><!-- /input-group -->
				</ul>
				{!! Form::close() !!}
                </div><!-- col-sm-7 -->
 <!--                <div class="col-md-3 col-md-offset-2" style="padding-left:20px;padding-top:18px;margin-right:-1%;">
                    @if(Auth::check())

                    <h4 class="text-right">OpenCredit Balance:</h4>
                    <strong class="pull-right"
                    style="color:#73D2C6;font-size:1.2em;margin-top:-10px;">
                    Points
                    {{number_format($ocredit,2)}}
                    </strong>
                    @endif
                </div> -->
                <!-- <div class="col-md-2  cart-holder">
                    @if( Cart::totalItems() < 1)
                    <div class="cart">
                        <a href="{{URL::to('cart')}}" class='cart-link'> Cart is Empty  </a>
                        <i class="fa fa-shopping-cart"></i> <span class="badge badge-cart"> 0 </span>

                    </div>
                    @else
                    <div class="cart">
                        <a href="{{URL::to('cart')}}"> View Cart  </a>
                        <i class="fa fa-shopping-cart"></i> <span class="badge badge-cart"> {!! Cart::totalItems() !!} </span>
                    </div>
                    @endif
                </div> -->
            </div>
        </div>
        </div>
    </div>
<script type="text/javascript">
//     $('.selectable > li > a').click( function( e ) {
//     e.preventDefault();
//     $('.selectable').find('li').removeClass('cat');
//     $(this).parent().addClass('cat');
// });
</script>

    <!--Begin Top Navigation-->
    <nav class="navbar navbar-inverse megamenu  navbar-static-top omenu container" style="margin-bottom: 0 !important;margin-top: -10px;background-color: black;padding-left: 10px">
        <div class="">
        <div class="">

            <!-- Brand and toggle get grouped for better mobile display -->

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand hidden" href="#"></a>
            </div>



            <div class="collapse navbar-collapse" id="main-menu">
                <ul class="nav navbar-nav selectable">
                    <li class="{{ $cf->set_active('/') }}">
                        <a style="padding-right:10px;color: white"
                            href="{{URL::to('/')}}">Home</a></li>
                    <li class="{{ $cf->set_active('category') }} dropdown mega-dropdown catmenu">
                        <a style="margin-left:0;padding-left:10px;padding-right:10px"
                            href="javascript:void(0)" id="show-category-list" class="catl">
                            Category <span class="caret"></span></a>
                    </li>
                    <li class="{{ $cf->set_active('brand') }} dropdown mega-dropdown dropdown-menu-right barnddes">
                        <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                            href="javascript:void(0)" id="show-brand-list">
                            Brand <span class="caret"></span></a>
                    </li>
                    {{-- NavBar Ends zxcv --}}

                    <li class="{{ $cf->set_active('SMM') }} dropdown mega-dropdown smmdes">
                        <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                            href="javascript:void(0)" id="show-smm-list">
                            SMM <span class="caret"></span></a>
                    </li>
   <li class="{{ $cf->set_active('owarehouselist') }} dropdown mega-dropdown hypers">
                       <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                            href="javascript:void(0)" id="show-hyper-list">
							Hyper <span class="caret"></span></a>
                    </li>
                   {{--  <script type="text/javascript">
                        // Check if SMM registered
                        $(document).ready(function(){
                            $('#header_smm').click(function(){
                                alert("wow");
                                url="{{url('check/smm/merchant/status')}}";
                            $.ajax({
                                url:url,
                                type:'GET',
                                success:function(r){
                                    if (r.status=="success") {
                                        if (r.short_message=="cl"){
                                            toastr.info("As a merchant please register for SMM");
                                        }
                                    }
                                }
                            });
                        });
                            });

                    </script> --}}
                    {{--<li class="dropdown {{ $cf->set_active('station') }}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="false">
                            Station <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class=""><a href="{{URL::to('/edit_station')}}">Station Information</a></li>
                            <li class=""><a href="{{URL::to('/')}}">Station DashboardXXXX</a></li>
                            <li class=""><a href="{{URL::to('/station/open-channel')}}">Open Channel</a></li>
                            <li class=""><a href="{{URL::to('/station/order-view')}}">Order View</a></li>
                            <li class=""><a href="{{URL::to('/')}}">Delivery Order</a></li>
                            <li class=""><a href="{{URL::to('/')}}">Inventory Report</a></li>
                            <li class=""><a href="{{URL::to('/station/administration')}}">Administration</a></li>
                            <li class=""><a href="{{URL::to('/')}}">Station Overall View</a></li>
                        </ul>
                    </li>--}}
				   <?php 
						$imm = null;
						if(Auth::check()){
							$imm = DB::table('merchant')->where('user_id',Auth::user()->id)->first();
						}
					?>
                   @if(Auth::check() && (Auth::user()->hasRole('mer') || Auth::user()->hasRole('sto') || Auth::user()->hasRole('hcu') || Auth::user()->hasRole('fmu') || !is_null($imm)))
                       @if(Auth::user()->hasRole('log'))
                           <li class="{{$cf->set_active('logistic_dashboard')}}">
                                <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                                href="{{URL::to('/logistic_dashboard')}}">Logistics</a></li> 
                       @endif

                       @if((!Auth::user()->hasRole('log') && Auth::user()->hasRole('sto')) || Auth::user()->hasRole('mer') || Auth::user()->hasRole('hcu') || Auth::user()->hasRole('fmu') || !is_null($imm))
						   @if(Auth::user()->hasRole('mer') || Auth::user()->hasRole('hcu') || Auth::user()->hasRole('fmu') || !is_null($imm))
                            <li class="{{$cf->set_active('seller')}}
                                {{$cf->set_active('edit_merchant')}}
                                {{$cf->set_active('edit_station')}}
                                {{$cf->set_active('album')}}
                                {{$cf->set_active('dashboard')}}
                                {{$cf->set_active('seller/buyingorder')}}
                                {{$cf->set_active('seller/buyingreceipt')}}
                                {{$cf->set_active('seller/likes')}}
                                {{$cf->set_active('station/ochannel-station')}}
                                {{$cf->set_active('open-channel')}}
                                {{$cf->set_active('station/ochannel-supplier')}}
                                {{$cf->set_active('station/order-view-icon')}}
                                {{$cf->set_active('station/order-view')}}
                                {{$cf->set_active('inventory/update')}}
                                {{$cf->set_active('station/salesreport')}}
                                {{$cf->set_active('station/inventory-report')}}
                                {{$cf->set_active('merchant/salesreport')}}">
                                <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                                href="{{URL::to('/edit_merchant')}}">Seller</a></li>  
							@else
								<li class="{{$cf->set_active('seller')}}
                                {{$cf->set_active('edit_merchant')}}
                                {{$cf->set_active('edit_station')}}
                                {{$cf->set_active('album')}}
                                {{$cf->set_active('dashboard')}}
                                {{$cf->set_active('seller/buyingorder')}}
                                {{$cf->set_active('seller/buyingreceipt')}}
                                {{$cf->set_active('seller/likes')}}
                                {{$cf->set_active('station/ochannel-station')}}
                                {{$cf->set_active('open-channel')}}
                                {{$cf->set_active('station/ochannel-supplier')}}
                                {{$cf->set_active('station/order-view-icon')}}
                                {{$cf->set_active('station/order-view')}}
                                {{$cf->set_active('inventory/update')}}
                                {{$cf->set_active('station/salesreport')}}
                                {{$cf->set_active('station/inventory-report')}}
                                {{$cf->set_active('merchant/salesreport')}}">
                                <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                                href="{{URL::to('/edit_station')}}">Seller</a></li> 
							@endif
							
							

                    {{-- Statement has been moved down to Dashboard!
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle"
                        style="margin-left:0;padding-left:10px;padding-right:10px"
                        data-toggle="dropdown" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        Statement<span class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <li class="{{$cf->set_active('receipt')}}">
                                <a href="{{ url('statement/receipt') }}">
                                    Receipt</a>
                            </li>
                            <li class="{{$cf->set_active('deliveryorder')}}">
                                <a href="{{ url('statement/delivery-order') }}">
                                    Delivery Order</a>
                            </li>
                            <li class="{{$cf->set_active('overallstatement')}}">
                                <a href="{{ url('statement/overall-statement') }}">
                                    Overall Statement</a>
                            </li>
                        </ul>
                    </li>
                    --}}

                    @endif
                    @endif
                     {{-- For those users who are logged in, we test for the admin role --}}
                @if (Auth::check() && Auth::user()->hasRole('adm'))
                    <li class="">
                        <a style="margin-left:0;padding-left:10px;padding-right:10px" 
                            href="{{route('adminPanel')}}" class=""
                            data-toggle="" role="button" aria-haspopup="true"
                            aria-expanded="false">Admin<span class=""></span></a>
                        <!--
                        <ul class="dropdown-menu">
                            <li class="{{$cf->set_active('buyerinformation')}}">
                                <a href="#">Merchant Approval</a>
                            </li>
                            <li class="{{$cf->set_active('buyerinformation')}}">
                                <a href="{{route('tblmgmt')}}">
                                    Table Management</a>
                            </li>
                        </ul>
                        -->
                    </li>
               @endif

            </ul>
   <style type="text/css">


.badges {
    background-color:  #f14141;
    padding: 6px 12px;
    text-align: center;
    z-index: 0;
 
    font-weight: normal;
    font-size: 11px;
    right: 0;
    top: -1px;
    bottom: auto;
    vertical-align: text-top;
    border-radius: 5px;
}

   </style>
<ul>
        <?php 
                $ocredit=0;
                if (Auth::check()) {
                    # code...
                    
                   // $oacc= DB::table('ocredit_acct')->where('user_id',Auth::user()->id)->first();
                    $oacc=UtilityController::ocredit()['ocredit'];
                    if (is_null($oacc)) {
                        # code...
                        $ocredit=0;
                    }
                    else{
                        $ocredit=$oacc/100;
                    }
                }
                // if (!Auth::check()) {
                //     $ocredit=0;
                // }

           ?>
			<li class="dropdown mega-dropdown text-right pull-right" style="margin-top: -5px;">
			@if(Auth::check())
			<h4 class="text-center"
			style="font-size:16px !important; float: left;padding-top: 10px; margin-bottom: 0px !important; color: #FFF;">
			<b>OpenCredit&nbsp;&nbsp;</b>{{number_format($ocredit,2)}} Pts
			</h4>
			@endif 
			@if( Cart::totalItems() < 1)
			<a href="{{URL::to('cart')}}" class=''
			style="margin-left:0;padding-left:10px;padding-right:10px; color: #34dabb;padding-top: 15px;padding-right:0;">
			<i class="fa fa-shopping-cart"
			style="font-size: 42px;height: 20px;">
			<span class="badges"
			style="opacity: 1; display: none; color: white;font-size:14px;">
			</span></i>
			<span style="font-size: 15px;"
			class="cart-link">Cart is Empty</span>  
			</a>
			@else
			<a href="{{URL::to('cart')}}" class=''
			style="margin-left:0;padding-left:10px;padding-right:10px; color: #34dabb;padding-top: 15px;padding-right:0;"> <i class="fa fa-shopping-cart"
			style="font-size: 42px;height: 20px;">
			<span class="badges"
			style="opacity: 1;color: white;font-size: 14px;">
			{!! Cart::totalItems() !!}
			</span></i> <span style="font-size: 15px;"
			class="cart-link">View Cart</span>  </a>
			@endif
		</li>
		</div><!-- /.navbar-collapse -->
        </div>
        </div>
    </nav>

    <div class="container head-container ominimenu">
        <div class="row">
            <div class="col-md-12">
                <div id="headerCategory" class="row"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="headerSMM" class="row"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="headerHyper" class="row"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="headerBrand" class="row masonry-megamenu">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs brand-tabs">
                            <?php foreach (range('A', 'Z') as $letter): $active = ($letter == 'A') ? ' class="active"' : null; ?>
                                <li<?php echo $active ?>><a data-toggle="tab" href="#B<?php echo $letter ?>"><?php echo $letter ?></a></li>
                            <?php endforeach ?>
                            <li><a data-toggle="tab" href="#B0">[0-9]</a></li>
                            <li><a data-toggle="tab" href="#BSC">*</a></li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="row tab-content">
                            @include('common.mini.header_brand')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Top Navigation-->
    <!--Begin BreadCrumb-->
    <div class="container head-container">
        <div class="row">
            <div class="col-sm-12">
                @yield('breadcrumbs')
            </div>
        </div>
    </div>
    <!--End BreadCrumb-->
 <style>
    /* Prevents slides from flashing */
    #slides {
      display:none;
    }
    #slides li{
        display: hidden;
    }

    /*Fixed extra space after nav*/
     .head-container .col-md-12,
     .head-container .col-sm-12{
         min-height: 0 !important;
     }
  </style>
<?php

    $showAds=False;
    $auto="true";
    $rottime=5000;
    $height=450;
    $width=600;
    // Get current URI
    $currentPath= Route::getFacadeRoot()->current()->uri();
	
    // Get target_id
    $AdTarget=AdTarget::where('route',$currentPath)->first();
	
    // Find AdControl for the adTarget
    if (!is_null($AdTarget)) {
		// $images = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
		// 								->join('adimage','adimage.advertisement_id','=','advertisement.id')
		// 								->join('adtarget','adslot.adtarget_id','=','adtarget.id')
		// 								->select('advertisement.*','adimage.path')
		// 								->where('adtarget.id','=',$AdTarget->id)
		// 								->where('adslot.placement','=','A1')->get();
		// $showAds=True;
    }
?>
@if($showAds==True)
<div class="header" style="padding-top:0;margin-top:0">
{{-- <img src=""> --}}
    <div id="slides" style="padding-top:0;margin-top:0;border:0px solid red; background-color: #FFF;">
        @foreach($images as $i)
        <?php $imgpath='/images/advertisement/'.$i->id.'/'.$i->path; ?>
        <img src="{{asset($imgpath)}}" class="urlredirect" urlrel="{{URL::to('/')}}/{{$i->url}}" width="100%;" height="100%;"
        style="margin-top:0;padding-top:0;object-fit:none; cursor: pointer;">
        @endforeach
    </div>
  
</div>
@endif
</header>

<script>
    $(document).ready(function () {
		$(document).delegate( '.mobilecat', "click",function (event) {
			var cat_id = $(this).attr("rel");
			console.log(cat_id);
			$("#mobilecatplus" + cat_id).html("<img src='"+JS_BASE_URL+"/images/category/mobileminus.png' width='20px'/>");
			//$("#mobilecatplus" + cat_id).css("background-color","#73D2C6");
			$("#mobilecat" + cat_id).css("font-weight","bold");
			$("#mobilesubcatmenu" + cat_id).show();
			$(this).removeClass('mobilecat');
			$(this).addClass('mobilecathide');
		});
		
		$(document).delegate( '.mobilecathide', "click",function (event) {
			var cat_id = $(this).attr("rel");
			$("#mobilecatplus" + cat_id).html("<img src='"+JS_BASE_URL+"/images/category/mobileplus.png' width='20px'/>");
			//$("#mobilecatplus" + cat_id).css("background-color","transparent");
			$("#mobilesubcatmenu" + cat_id).hide();
			$(this).addClass('mobilecat');
			$("#mobilecat" + cat_id).css("font-weight","normal");
			$(this).removeClass('mobilecathide');
		});			
		
    	$('#signin_on_mobile').click(function(){
    		$('.mobile_login_form').submit();
    	});
		
		$('.systemmenu').on('click', function() {
			
			$('.dropdown-content-systembuyer').show();
		});
		
		$('.systemmenuseller').on('click', function() {
			
			$('.dropdown-content-systemseller').show();
		});
		
		$('.systemmenuadmin').on('click', function() {
			
			$('.dropdown-content-systemadmin').show();
		});
		
		$('.loginmobile').on('click', function() {
			
			$('.dropdown-content-login').show();
		});
		
		$('.forgot_password').on('click', function() {			
			$('.dropdown-content-forgot').show();
		});
		
		$('.glyphsearch').on('click', function() {
			$('.searchingmenu').show();
			$('.mobileheadermain').hide();
		});
		
		$('.glyphsearchclose').on('click', function() {
			$('.searchingmenu').hide();
			$('.mobileheadermain').show();
		});
		
		$('.closesystemmenuseller').on('click', function() {	
			$('.dropdown-content-systemseller').hide();
		});
		
		$('.closesystemmenuadmin').on('click', function() {	
			$('.dropdown-content-systemadmin').hide();
		});
		
		$('.closesystemmenu').on('click', function() {	
			$('.dropdown-content-systembuyer').hide();
		});
		
		$('.closemobilelogin').on('click', function() {	
			$('.dropdown-content-login').hide();
		});
		
		$('.closemobileforgot').on('click', function() {	
			$('.dropdown-content-forgot').hide();
		});
		$('.closemobilemenu').on('click', function() {
			// console.log("CLOSE");
			$(".dropdown-content-menu").hide();
		});
		$('#first-menu').on('click', function() {
			$(".dropdown-content-menu").toggle();
		});
		$('.urlredirect').on('click', function() {
			var url = $(this).attr("urlrel");
			if(url != ""){
				window.open(url, '_blank');
			}
		});
		
        $('#loginout').on('click', function () {
            window.history.go(-window.history.length);
        });
		
		$('#loginout2').on('click', function () {
            window.history.go(-window.history.length);
        });
        $("#slides").slidesjs({
            width:{{$width}},
            height:{{$height}},
            navigation:{
                active:true,
                effect:"slide"
            },
            play: {
              active: true,
                // [boolean] Generate the play and stop buttons.
                // You cannot use your own buttons. Sorry.
              effect: "slide",
                // [string] Can be either "slide" or "fade".
              interval:{{$rottime}},
                // [number] Time spent on each slide in milliseconds.
              auto:{{$auto}},
                // [boolean] Start playing the slideshow on load.
              swap: true,
                // [boolean] show/hide stop and play buttons
              pauseOnHover: false,
                // [boolean] pause a playing slideshow on hover
              restartDelay: 2500
                // [number] restart delay on inactive slideshow
            },
            callback: {
                loaded: function(number) {
                    $('.slidesjs-pagination, .slidesjs-navigation').hide(0); 
                }
            }
        });
        $(function () {
		  var all_classes = "";
		  var timer = undefined;
		  $.each($('li', '.social-class'), function (index, element) {
		    all_classes += " btn-" + $(element).data("code");
		  });
		  $('li', '.social-class').mouseenter(function () {
		    var icon_name = $(this).data("code");
		    if ($(this).data("icon")) {
		      icon_name = $(this).data("icon");
		    }
		    var icon = "<i class='fa fa-" + icon_name + "'></i>";
		    $('.btn-social', '.social-sizes').html(icon + "Sign in with " + $(this).data("name"));
		    $('.btn-social-icon', '.social-sizes').html(icon);
		    $('.btn', '.social-sizes').removeClass(all_classes);
		    $('.btn', '.social-sizes').addClass("btn-" + $(this).data('code'));
		  });
		  $($('li', '.social-class')[Math.floor($('li', '.social-class').length * Math.random())]).mouseenter();
		});
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
    })
;</script>
