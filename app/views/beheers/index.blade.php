@extends('layouts.default')

<?php
$isAdmin = Sentry::check() && Sentry::getUser()->hasAccess('admin');
?>
{{-- Web site Title --}}
@section('title')
@parent
{{trans('beheer.beheer')}}
@stop

{{-- Content --}}
@section('content')

<div class='container-fluid col-md-offset-3 col-md-6 roodkader'>
  @if ($isAdmin)
  	<ol>
  	  <li> <a href="{{ URL::to('beheer/init') }}">{{trans('beheer.init')}}</a></li>
  	  <li> <a href="{{ URL::to('beheer/checkmail') }}">{{trans('beheer.checkmail')}}</a></li>
  	</ol>
  @else
  	Voor deze functie moet je meer rechten hebben
  @endif    
</div>

@stop
