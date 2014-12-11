@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('documents.title') }}
@stop

{{-- Content --}}
		{{-- om terug te keren zijn er verschillende mogelijkheden:
			
			1. als er geen titel is, dan keer je terug naar inhoud pagina
			2. als er titel is, dan keer je terug naar de volledige lijst
			--}}
		<?php
		if ($title)
		{
			$terug = url("volledigelijst", $parameters = array('rubriek' => $rubriek, 'title' => 'leeg'));
		} else {
			$terug = url("inhoud");
		}
		
		// ook nog $editor
		 $editor = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		?>
		
@section('content')
<h4 class='titeltekst'> {{ $rubriek }}</h4>

title = {{ $title}}
@stop