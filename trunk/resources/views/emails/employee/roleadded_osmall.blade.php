@extends('emails.common.layout')
@section('content')
	<strong>Hi, {{$user->first_name}}</strong>
	<p style="font-family:Helvetica,sans-serif;font-size:13px;line-height:20px;color:#505050;font-weight:none;">
		Congratulations! You have been appointed as {{$role->description}} by OpenSuperMall!
	</p>
	<p>Thank you,</p>
@stop