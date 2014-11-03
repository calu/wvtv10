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
	<div class='col-xs-4 '>midden1</div>	
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
	
	<div class='col-xs-4 '>links2</div>
		
	<div class='col-xs-4 '>midden2</div>
		
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

	
	<div class='col-xs-4 '>links3</div>	
	
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