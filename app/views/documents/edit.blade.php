@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('document.title')}}
@stop


<?php
	// terug naar rubriek overzicht
	$rubriek = $document->type;
	$terug = url("volledigelijst", $parameters = array('rubriek' => $rubriek, 'title' => 'leeg'));
	$choicesarray = DB::table('documents')->select(DB::raw('DISTINCT title'))->where('type',$rubriek)->get();
	foreach($choicesarray AS $choice)
	{
		$choices[$choice->title] = $choice->title;
	}
	
	
	// De datum
	if ($document->date == "0000-00-00") $document->date = null;
	else // wijzig het datum formaat
	{
		$document->date = AppHelper::formatUTC2European($document->date);
	}  
	
	$titelInLijn = $rubriek == 'links' || $rubriek = 'wetgeving';
?>

{{-- Content --}}
@section('content')


<h4 class='titeltekst'>{{trans('document.actionedit')}}</h4>

<div class='row'>
	<a href="{{ $terug }}" class='groen'>terug naar inhoud</a>
</div>

@if ($errors->has())
	@foreach($errors->all() as $message)
		<div class='well'>
			{{ $message }}
		</div>
	@endforeach
@endif
<div class="well">
	{{ Form::open(array(
        'action' => array('DocumentsController@update', $document->id), 
        'method' => 'put',
        'class' => 'form-inline', 
        'files' => true,
        'role' => 'form'
        )) }}
        
        @if ($titelInLijn)
	        <div class='form-group fullwidth'>
	        	{{ Form::label('edit-newtitle', trans('document.edit-linetitle'), array('class' => 'label-120 control-label' )) }}
	        	{{ Form::text('newtitle', $document->title, array('class' => 'mycol-600', 'placeholder' => trans('document.edit-linetitle'), 'id' => 'edit-newtitle' )) }}
	        </div>        
        @else
        <div class='roodkader'>
	        <div class='form-group fullwidth'>
	        	{{ Form::label('edit-title', trans('document.edit-title'), array('class' => 'label-120 control-label' )) }}
	        	{{ Form::select('title', $choices, $document->title, array('class' => 'mycol-600', 'placeholder' => trans('document.edit-title'), 'id' => 'edit-title' )) }}
	        	{{ ($errors->has('title') ? $errors->first('country') : '')}}
	        </div>
	        <div class='clearfix'>&nbsp;</div>
	        <div class='form-group fullwidth'>
	        	{{ Form::label('edit-newtitle', trans('document.edit-newtitle'), array('class' => 'label-120 control-label' )) }}
	        	{{ Form::text('newtitle', '', array('class' => 'mycol-600', 'placeholder' => trans('document.edit-newtitle'), 'id' => 'edit-newtitle' )) }}
	        </div>
        </div>
        @endif
        <div class='clearfix'>&nbsp;</div>        
        <div class='form-group fullwidth'>
        	{{ Form::label('edit-description', trans('document.edit-description'), array('class' => 'label-120 control-label' )) }}
        	{{ Form::textarea('description', $document->description, array('class' => 'mycol-600', 'size' => '80x4', 'placeholder' => trans('document.edit-description'), 'id' => 'edit-description' )) }}
        	{{ ($errors->has('description') ? $errors->first('description')  : '' ) }}
        </div>
        <div class='clearfix'>&nbsp;</div>
        <div class='form-group fullwidth'>
        	{{ Form::label('edit-date', trans('document.edit-date'), array('class' => 'label-120 control-label' )) }}
        	{{ Form::text('date', $document->date, array('class' => 'mycol-200 datepicker', 'placeholder' => trans('document.edit-date'), 'data-datepicker' => 'datepicker', 'id' => 'edit-date' )) }}
        	{{ ($errors->has('date') ? $errors->first('date') : '' ) }}
        </div>
        <div class='clearfix'>&nbsp;</div>
        <div class='form-group fullwidth'>
        	{{ Form::label('edit-author', trans('document.edit-author'), array('class' => 'label-120 control-label' )) }}
        	{{ Form::text('author', $document->author, array('class' => 'mycol-400', 'placeholder' => trans('document.edit-author'), 'id' => 'edit-author' )) }}
        	{{ ($errors->has('author') ? $errors->first('author') : '' ) }}
        </div>
        <div class='clearfix'>&nbsp;</div>
        <div class='roodkader'>
        	<div class='bg-info'>
        		De naam van het bestand in de onderstaande url = {{ $document->localfilename }}
        	</div>
        	<div class='clearfix'>&nbsp;</div>
        	<div class='form-group fullwidth'>
        		{{ Form::label('edit-url', trans('document.edit-url'), array('class' => 'label-120 control-label' )) }}
        		{{ Form::text('url', $document->url, array('class' => 'mycol-600', 'placeholder' => trans('document.edit-url'),'id' => 'edit-url' )) }}
        		{{ ($errors->has('url') ? $errors->first('url') : '' ) }}
        	</div>
        	<div class='clearfix'>&nbsp;</div>
        	<div class='form-group fullwidth'>
        		{{ Form::label('edit-file', trans('document.edit-file'), array('class' => 'label-200 control-label' )) }}
        		{{ Form::file('file', array('id' => 'edit-file'))}}
        	</div>
        </div> 
        <div class='clearfix'>&nbsp;</div>
        <div class='form-group fullwidth'>
        	{{ Form::label('edit-alwaysvisible', trans('document.edit-alwaysvisible'), array('class' => 'label-120 control-label' )) }}
        	{{ Form::checkbox('alwaysvisible', $document->alwaysvisible, $document->alwaysvisible, array('id' => 'edit-alwaysvisible')) }}
        </div>
        <div class='clearfix'>&nbsp;</div>         

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {{ Form::hidden('id', $document->id) }}
                {{ Form::submit(trans('pages.actionedit'), array('class' => 'btn btn-primary'))}}
            </div>
      </div>
    {{ Form::close()}}
</div>

<div class='row'>
	<a href="{{ $terug }}" class='groen'>terug naar inhoud</a>
</div>

@stop