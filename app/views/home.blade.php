@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('pages.title')}}
@stop

{{-- Content --}}
@section('content')

{{-- hier komen alle sponsors met hun logo --}} 

<div class="row">
  <div class="col-md-offset-3 col-md-2 ">
    <a href="http://www.rodekruisvlaanderen.be" class="thumbnail" target='_new'>
      {{HTML::image('img/sponsors/rklogo01.png') }}
    </a>
  </div>
  <div class="col-md-2">

	    <a href="http://www.macopharma.com" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/macopharma.png') }}
	    </a>  	 	


	    <a href="http://www.caf-dcf.dotnet17.hostbasket.com/" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/cafdcf.png') }}
	    </a>  	 	
 	 
  </div>

  <div class="col-md-2">

	    <a href="http://www.orthoclinical.com/en-be/Pages/Home.aspx" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/ocd_logo_color.png') }}
	    </a>  	 	

	    <a href="http://www.fresenius-kabi.com" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/fresenius-kabi.png') }}
	    </a>  	 	
 	 
  </div>  
</div>

<div class="row">
  <div class="col-md-offset-3 col-md-2 ">
    <a href="http://www.fenwalinc.eu/" class="thumbnail" target='_new'>
      {{HTML::image('img/sponsors/fenwallogo.png') }}
    </a>
  </div>
  <div class="col-md-2">

	    <a href="http://cerus.com" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/cerus_logo.png') }}
	    </a>  	 	
	 
  </div>

  <div class="col-md-2">

	    <a href="http://www.sarstedt.com/php/main.php" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/sarsted_s.png') }}
	    </a>  	 	
	 
  </div>  
</div>

<div class="row">
  <div class="col-md-offset-3 col-md-2 ">
    <a href="http://www.terumo-europe.com/" class="thumbnail" target='_new'>
      {{HTML::image('img/sponsors/terumologo_s.png') }}
    </a>
  </div>
  <div class="col-md-2">

	    <a href="http://www.octapharma.com" class="thumbnail" target='_new'>
	      {{HTML::image('img/sponsors/octa_ms_ReflexBlue.png') }}
	    </a>  	 		 
  </div>
</div>

<div class='row'>
	<div class='col-md-offset-3 col-md-2'>
		{{ link_to_route('disclaimer', 'Disclaimer', null, array('class' => 'btn btn-default')) }} 
	</div>
</div>

 
@stop