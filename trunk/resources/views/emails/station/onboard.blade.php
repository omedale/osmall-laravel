@extends('emails.common.layout')
@section('content')
	<strong>Hi, {{$user->name}}</strong>
	<p style="font-family:Helvetica,sans-serif;font-size:13px;line-height:20px;color:#505050;font-weight:none;"">
		Thank you for registering with us. Your approval is under process, please wait for 1-3 working days.
		For any questions/comments contact support.
	</p>
@stop
