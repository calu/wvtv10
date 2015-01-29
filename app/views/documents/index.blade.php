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
		if ($title != 'leeg')
		{
			$terug = url("volledigelijst", $parameters = array('rubriek' => $rubriek, 'title' => 'leeg'));
		} else {
			$terug = url("inhoud");
		}
		
		// ook nog $editor
		 $editor = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		 $rubriekpointer = AppHelper::getRubriekpointer($rubriek);
		 $urlnieuw = url("documentcreate", array('rubriek' => $rubriek));
		 
		 // nu de rijinhoud samenstellen
		 $titelInLijn = $rubriek == 'links' || $rubriek == 'wetgeving';
		 
		 if ($editor) { $headers[] = ""; }
		 if ($titelInLijn)$headers[] = 'Titel';
		 $headers[] = 'Omschrijving';
		 $headers[] = 'Datum';
		 $headers[] = 'Link';
		 if ($editor) { $headers[] = ""; }

		 // De content ophalen
		 
		 // als (!titelInLijn en title == leeg) --> haal de lijst op van alle rubrieken
		 $obj = new StdClass();
		 $obj->title = $title;
		 $rubriekenlijst = array($obj);
		 		
		 if (($titelInLijn || $title == 'leeg'))
		 {
			 $rubriekenlijst = DB::table('documents')
			 					->select(DB::raw('DISTINCT title'))
								->where('type', $rubriek)
								->orderBy('sortnr')
								->get();		 	
		 }	else {
		 	$rubriekenlijst = array('titel');
		 }	 
		 
		 // Nu we de kolommen hebben berekend, moeten we de kolombreedte (aantalkolommen) berekenen voor de titellijn
		 // Als het echter een beheerder is en er is een titel! dan moeten we er de up/down pijltjes voorzetten
		 // en daarom verminderen we het aantal kolommen met 1
		 // tenzij er maar 1 kolom is, want dan moet je de pijltjes ook niet toon
		 $slechtsEenTitel = sizeof($rubriekenlijst) == 1;
		 
		 $aantalkolommen = sizeof($headers);
		 if (!$titelInLijn && $editor && !$slechtsEenTitel) $aantalkolommen--;
		 		 		 
//		 $inhoud = AppHelper::getFullList($rubriek, $title);
//		 var_dump($inhoud);

		 
		?>
		
@section('content')
<h4 class='titeltekst'> {{ $rubriek }}</h4>

<div class='row'>
	<a href="{{ $terug }}" class='groen'>terug naar inhoud</a>
</div>

@if ($editor)
	<div><a href="{{ $urlnieuw }}">Een nieuw item toevoegen</a></div>
@endif

<div class='table-responsive'>
	{{-- Als je geen rubriekenlijst heb haal je de volledige inhoud op --}}
	@if ($titelInLijn)

		<?php $inhoud = AppHelper::getFullList($rubriek,''); ?>
		{{-- var_dump($inhoud); die("jkdjjlj") --}}
		{{-- toon nu de volledige tabel --}}
		<table class='table table-bordered'>
			<thead>
				<tr>
					@foreach ($headers AS $header)
						<th>{{ $header }}</th>
					@endforeach					
				</tr>
			</thead>
			<tbody>
				@foreach( $inhoud AS $rij)			
				<tr>
					@if ($editor)
				    	<?php
				    		$urlup = url('arrow', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek, 'direction' => 'up'));
							$urldown = url('arrow', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek, 'direction' => 'down'))
				    	?>					
				    	<td class='mycol-70'>
				    		<a href="{{ $urlup }}" rel='tooltip'>{{ HTML::image('img/up.png') }}</a>
				    		<a href="{{ $urldown }}" rel='tooltip'>{{ HTML::image('img/down.png') }}</a>
				    	</td>					
					@endif
					
					@if ($rij->alwaysvisible ==1 || $editor)
						@if ($titelInLijn)
							<td> {{ $rij->title }}</td>
						@endif
						<td>{{ $rij->description }}</td>
						<td>{{ $rij->date }}</td>
						<td>{{ $rij->url }} 
							<a href="{{ url($rij->url) }}" target='_new'>link</a>
						</td>
					@endif
					
			    	@if ($editor)
				    	<?php
				    		$urledit = url('edit', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek));
							$urldelete = url('delete', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek));
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
	@else
	@foreach ($rubriekenlijst AS $titleObject)
		<?php $inhoud = AppHelper::getFullList($rubriek, $titleObject->title); ?>

		{{-- toon nu een volledige tabel --}}
		<table class='table table-bordered'>
			<thead>
				<tr>
					@if (!$titelInLijn && $editor && !$slechtsEenTitel)
				    	<?php
				    		$id = $inhoud[0]->id;
							
				    		$urlup = url('arrow', $parameters = array('id' => $id, 'rubriek' => $rubriek, 'direction' => 'up', 'isTitle' => 'yes'));
							$urldown = url('arrow', $parameters = array('id' => $id, 'rubriek' => $rubriek, 'direction' => 'down', 'isTitle' => 'yes'))
				    	?>					
				    	<td class='mycol-70'>
				    		<a href="{{ $urlup }}" rel='tooltip'>{{ HTML::image('img/up.png') }}</a>
				    		<a href="{{ $urldown }}" rel='tooltip'>{{ HTML::image('img/down.png') }}</a>
				    	</td>						
					@endif
					<td colspan="{{ $aantalkolommen }}" class='rubriektitel'>{{ $inhoud[0]->title }}</td></tr>
				<tr>
					@foreach ($headers AS $header)
						<th>{{ $header }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				
				@foreach( $inhoud AS $rij)			
				<tr>
					@if ($editor)
				    	<?php
				    		$urlup = url('arrow', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek, 'direction' => 'up'));
							$urldown = url('arrow', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek, 'direction' => 'down'))
				    	?>					
				    	<td class='mycol-70'>
				    		<a href="{{ $urlup }}" rel='tooltip'>{{ HTML::image('img/up.png') }}</a>
				    		<a href="{{ $urldown }}" rel='tooltip'>{{ HTML::image('img/down.png') }}</a>
				    	</td>					
					@endif
					
					@if ($rij->alwaysvisible ==1 || $editor)
						@if ($titelInLijn)
							<td> {{ $rij->title }}</td>
						@endif
						<td>{{ $rij->description }}</td>
						<td>{{ $rij->date }}</td>
						<td>{{ $rij->url }} 
							<a href="{{ url($rij->url) }}" target='_new'>link</a>
						</td>
					@endif
					
			    	@if ($editor)
				    	<?php
				    		$urledit = url('edit', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek));
							$urldelete = url('delete', $parameters = array('id' => $rij->id, 'rubriek' => $rubriek));
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
	@endforeach
	@endif
</div>


<div class='row'>
	<a href="{{ $terug }}" class='groen'>terug naar inhoud</a>
</div>
@stop