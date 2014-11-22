<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title> 
			@section('title') 
			@show 
		</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Bootstrap 3.0: Latest compiled and minified CSS -->
		<!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

		<!-- Optional theme -->
		<!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css"> -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}">

		{{-- <link rel="stylesheet" href="{{asset('css/own.css')}}"> --}} <!-- toegevoegd -->
		{{ stylesheet_link_tag() }}
		{{ javascript_include_tag() }}

		{{HTML::style('http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css')}}
		{{HTML::script('http://code.jquery.com/jquery-1.9.1.js')}}
		{{HTML::script('http://code.jquery.com/ui/1.10.4/jquery-ui.js')}}

		<script>
		$(function() {
			$( ".datepicker" ).datepicker({
				changeMonth : true,
				changeYear : true,
				dateFormat : "dd-mm-yy",
				yearRange: "1900:+0"
				});
			});
		</script>
		
		<style>
		@section('styles')
			body {
				padding-top: 90px; {{-- origineel 60px --}}
			}
		@show
		</style>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	
	</head>

	<body>
		

		<!-- Navbar -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<ul class="nav navbar-nav">
				<li class="active">
					<a href="{{ URL::route('home') }}" style='margin:0;padding:0'>
						{{HTML::image('img/logowvtv.jpg','alt logo', array('width' => '60px', 'vertical-align' => 'text-top') )}}
						<span style='line-height: 60px; text-align:center; color : red;	font-size : 1.6em;'>WVTV</span>
					</a>					
				</li>
				<li class='menu'>
					<a class='titel' href="{{ URL::to('/inhoud') }}">{{trans('default.inhoud')}}</a>
				</li>
				<li class='menu'>
					<a class='titel' href="{{URL::to('/statuten') }}">{{trans('default.statuten') }}</a>
				</li>
				<li class='menu'>
					<a class='titel' href="{{URL::to('/contact') }}">{{trans('default.contact') }}</a>
				</li>	
				
				@if (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')))
					<li class='menu'>
						<a class='titel' href="{{ URL::to('/beheer') }}">{{trans('default.beheer')}}</a>
					</li>
				@endif			
			</ul>
			
			<ul class='nav navbar-nav navbar-right'>
				@if (Sentry::check())
					<li {{ (Request::is('users/show/'.Session::get('userId')) ? 'class="active menu"' : 'class="menu"')}}>
						<a href="/users/{{ Session::get('userId') }}">{{ Session::get('email') }}</a>
					</li>
					<li class='menu'>
						<a href="{{ URL::to('logout') }}">{{trans('default.logout')}}</a>
					</li>
				@else
					<li {{ (Request::is('login') ? 'class="active menu"' : 'class="menu"') }}>
						<a href="{{ URL::to('login') }}">{{trans('default.login')}}</a>
					</li>
					<li {{ (Request::is('users/create') ? 'class="active menu"' : 'class="menu"') }}>
						<a href="{{ URL::to('users/create') }}">{{trans('default.register')}}</a>
					</li>
				@endif
			</ul>
		</div>
	
			<div class='titeltekst'>Wetenschappelijke Vereniging Transfusie Vlaanderen</div>
		</nav>
		<!-- ./ navbar -->

		<!-- Container -->
		<div class="container">
			<!-- Notifications -->
			@include('layouts/notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
		</div>

		<div class="container">
			<div class='col-md-offset-8 col-md-4'>
				Copyright &copy; {{ date('Y') }} by JC.<br/>
		        All Rights Reserved.<br/>
			</div>			
		</div>
		
		<!-- ./ container -->

		<!-- Javascripts
		================================================== -->
<?php /*		<script src="{{ asset('js/jquery-2.0.2.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('js/restfulizer.js') }}"></script>  */ ?>
		<!-- Thanks to Zizaco for the Restfulizer script.  http://zizaco.net  -->
	</body>
</html>
