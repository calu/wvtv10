@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('content.title') }}
@stop

{{-- Content --}}
@section('content')
<div class='row'>
	
	<div class='col-xs-4 '>
		<div class="panel panel-danger">
			<div class="panel-heading"">
				{{HTML::image('img/widgets/navorming.png') }}
				Navorming
			</div>
			<div class="panel-body">
				@include('contents.widget', array('rubriek' => 'navorming')) 
			</div>
		</div>
	</div>
	<div class='col-xs-4 '>
		<div class="panel panel-danger">
			<div class="panel-heading"">
				{{HTML::image('img/widgets/transfusie.png') }}
				Transfusie
			</div>
			<div class="panel-body">
				@include('contents.widget', array('rubriek' => 'transfusie')) 
			</div>
		</div>		
	</div>	
	<div class='col-xs-4'>
		<div class="panel panel-danger">
			<div class="panel-heading">
				{{HTML::image('img/widgets/bestuur.png') }}
				Bestuur</div>
			<div class="panel-body">
				@include('contents.widget', array('rubriek' => 'bestuur'))
			</div>
		</div>
	</div>		
</div>

<div class='row'>
	
	<div class='col-xs-4 '>
		<div class="panel panel-danger">
			<div class="panel-heading"">
				{{HTML::image('img/widgets/links.png') }}
				Navorming
			</div>
			<div class="panel-body">
				@include('contents.widget', array('rubriek' => 'links'))
			</div>
		</div>		
	</div>
		
	<div class='col-xs-4 '>
		<div class="panel panel-danger">
			<div class="panel-heading"">
				{{HTML::image('img/widgets/documentatie.png') }}
				Documentatie
			</div>
			<div class="panel-body">
				@include('contents.widget', array('rubriek' => 'document'))
			</div>
		</div>		
	</div>
		
	<div class='col-xs-4'>
		<div class='panel panel-danger'>
			<div class='panel-heading'>
				{{HTML::image('img/widgets/profiel.png') }}
						Profiel
			</div>
			<div class='panel-body'>
				@if (Sentry::check())
					@include('contents.widget', array('rubriek' => 'profiel'))
				@else
					als je aangemeld bent kan je hier je profiel en wachtwoord wijzigen
				@endif
			</div>
		</div>
	</div>
	
</div>

<div class='row'>

	
	<div class='col-xs-4 '>
		<?php
		$url1 = Url("geschiedenis/beknoptgesch.php");
		$url2 = Url("geschiedenis/landsteiner.html");
		$url3 = Url("geschiedenis/coombs.html");
		$url4 = Url("geschiedenis/wintrobe.html");
		?>
		{{-- geschiedenis --}}
		<div class='panel panel-danger'>
			<div class='panel-heading'>
				{{HTML::image('img/widgets/geschiedenis.png') }}
						Geschiedenis
			</div>
			<div class='panel-body'>
				<ul class='shorttable'>
					<li><a href='{{ $url1 }}' target='_new'>Beknopte geschiedenis van de transfusie</a></li>
					<li><a href='{{ $url2 }}' target='_new'>Karel L. Landsteiner</a></li>
					<li><a href='{{ $url3 }}' target='_new'>Robin R.A. Coombs</a></li>
					<li><a href='{{ $url4 }}' target='_new'>Maxwell M. Wintrobe</a></li>
				</ul>
			</div>
		</div>		
	</div>	
	
	<div class='col-xs-4 '>midden3</div>
	
	<div class='col-xs-4 '>
		<div class='panel panel-danger'>
			<div class='panel-heading'>
				{{HTML::image('img/widgets/twitter.png') }}
				Twitter
			</div>
			<div class='panel-body'>
				<a class="twitter-timeline" href="https://twitter.com/wvtvlaanderen" data-widget-id="380369285077405697">Tweets van @wvtvlaanderen</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>                
			</div>
		</div>
	</div>
		
</div>
@stop