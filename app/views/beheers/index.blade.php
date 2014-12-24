@extends('layouts.default')

<?php
$isAdmin = Sentry::check() && Sentry::getUser()->hasAccess('admin');
$isSysAdmin = Sentry::check() && Sentry::getUser()->id == 1;
?>
{{-- Web site Title --}}
@section('title')
@parent
{{trans('beheer.beheer')}}
@stop

{{-- Content --}}
@section('content')

<div class='container-fluid col-md-offset-3 col-md-6 roodkader'>
  	<ol>
  		@if ($isAdmin) 		
  	  <li> <a href="{{ URL::to('beheer/init') }}">{{trans('beheer.init')}}</a></li>
  	  <li> <a href="{{ URL::to('beheer/checkmail') }}">{{trans('beheer.checkmail')}}</a></li>
   		@endif 	  
  	  <li> <a href="{{ URL::to('beheer/editprofile') }}">{{trans('beheer.editprofile')}}</a></li>

  	  @if ($isSysAdmin)
  	  	<li><a href="{{ URL::to('beheer/restoredb') }}">{{trans('beheer.restoredb') }}</a></li>
  	  @endif

  	</ol>
</div>

@stop
