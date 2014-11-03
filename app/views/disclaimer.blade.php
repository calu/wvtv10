@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('pages.disclaimer')}}
@stop

{{-- Content --}}
@section('content')
<div class="col-md-offset-3 col-md-6 roodkader">
	<div class="row-fluid text-center">
		<h3><b>Wetenschappelijke Vereniging Transfusie Vlaanderen</b></h3>
	</div>
	<div class="row-fluid text-center">
		<h4><b>Vereniging zonder winstoogmerk</b></h4>
	</div>
	<div class="row-fluid">&nbsp;</div>
	<div class="row-fluid veldoffset">
		Deze website is uitsluitend informatief bedoeld voor geïnteresseerden in de materie en mag niet beschouwd
		worden als een substituut voor een specifiek medisch advies of diagnostische uitwerking. Door de WVTV of de
		webbeheerder wordt geen enkele juridische aansprakelijkheid aanvaard of enige garantie geboden ten aanzien
		van de alhier meegedeelde of beschreven opinies, visies of procedure.
	</div>
</div>
@stop