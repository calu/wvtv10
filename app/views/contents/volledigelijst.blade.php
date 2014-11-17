@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('content.fulllist') }}
@stop

<?php
$isAdmin = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
switch($rubriek)
{
	case 'bestuur' :
		$tabelHeader = Bestuur::getFullListTableHeader();
		$fulllist = Bestuur::getFullList();
		break;
	case 'navorming' :
		die("<br />[contents/volledigelijst] de rubriek {$rubriek} nog niet geïmplementeerd voor tabel");
		break;
	default :
		die("<br />[contents/volledigelijst] de rubriek {$rubriek} nog niet geïmplementeerd voor tabel");
}

$url = url('inhoud');
$rubriekpointer = AppHelper::getRubriekpointer($rubriek);
$urlnieuw = url("{$rubriekpointer}/create");
?>
{{--Content --}}
@section('content')

{{-- knop terugkeren --}}
   <div>
   	 <a href='{{ $url }}' class='groen'>keer terug</a>
   </div>
	
	{{-- de tabel met de lijst --}}
	<div class='titeltekst'>{{ $rubriek }}</div>
	
	{{-- een nieuw bestuurslid toevoegen --}}
	@if ($isAdmin)
		<div><a href="{{ $urlnieuw }}">een nieuw item toevoegen</a></div>
	@endif
	
	{{-- de tabel zelf --}}
	<div class='table-responsive'>
		<table class='table table_bordered'>
			<thead>
				<tr>
					@foreach($tabelHeader AS $headerItem)
						<th>{{$headerItem}}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($fulllist AS $element)
					<tr>
						{{ AppHelper::getFullListRow($rubriek, $element) }}
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	
{{-- knop terugkeren --}}
   <div>
   	 <a href='{{ $url }}' class='groen'>keer terug</a>
   </div>	
@stop
