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
			$terug = url("inhoud");
		
		// ook nog $editor
		 $editor = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		 
		if ($editor) $headers[] = "";
		$headers[] = "voornaam";
		$headers[] = "familienaam";
		if (Sentry::check())
		{
			$headers[] = "telefoon";
			$headers[] = "gsm";
			$headers[] = "e-mail";						
		}
		$headers[] = "functie";
		if ($editor) $headers[] = "";
		
		// de url voor het toevoegen van een nieuw beheerslid
		$urlnieuw = url("BestuursController/create");
		?>
		
@section('content')
<h4 class='titeltekst'> Bestuur</h4>

<div class='row'>
	<a href="{{ $terug }}" class='groen'>terug naar inhoud</a>
</div>

@if ($editor)
	<div>
		<a href="{{ $urlnieuw }}"><span class='rood'>TODO</span>Een nieuw item toevoegen</a>
	</div>
@endif

<div class = 'table-responsive'>
	<table class='table table_bordered'>
		<thead>
			<tr>
				@foreach ($headers AS $header)
					<th>{{ $header }}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach ($bestuurs AS $element)
			    <tr>
			    	@if ($editor)
			    	<?php
			    		$urlup = url('arrow', $parameters = array('id' => $element['id'], 'rubriek' => 'bestuur', 'direction' => 'up'));
						$urldown = url('arrow', $paramters = array('id' => $element['id'], 'rubriek' => 'bestuur', 'direction' => 'down'))
			    	?>
			    	<td class='mycol-70'>
			    		<a href="{{ $urlup }}" rel='tooltip'>{{ HTML::image('img/up.png') }}</a>
			    		<a href="{{ $urldown }}" rel='tooltip'>{{ HTML::image('img/down.png') }}</a>
			    	</td>
			    	@endif
			    	<td>{{ $element['first_name'] }}</td>
			    	<td>{{ $element['last_name'] }}</td>
			    	@if (Sentry::check())
			    		<td>{{ $element['phone'] }}</td>
			    		<td>{{ $element['gsm'] }}</td>
			    		<td>{{ $element['email'] }}</td>
			    	@endif
			    	<td>{{ $element['bestuursfunctie'] }}</td>	
			    	@if ($editor)
			    	<?php
			    		$urledit = url('edit', $parameters = array('id' => $element['id'], 'rubriek' => 'bestuur'));
						$urldelete = url('delete', $parameters = array('id' => $element['id'], 'rubriek' => 'bestuur'));
			    	?>
			    	<td>
			    		<a href="{{ $urledit }}" rel='tooltip'><span class="glyphicon glyphicon-pencil"></span></a>
			    		<a href="{{ $urldelete }}" rel='tooltip'><span class="glyphicon glyphicon-trash"></span></a>
			    	</td>
			    	@endif		    	
			    </tr>
			@endforeach
		</tbody>
	</table>
</div>
<div class='row'>
	<a href="{{ $terug }}" class='groen'>terug naar inhoud</a>
</div>
@stop