@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('bestuur.create')}}
@stop

{{-- Content --}}
@section('content')

<?php
$potentialusers = Bestuur::getPotentialusers();

?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
    	<h2 class='titeltekst'>{{trans('bestuur.create')}}</h2>
    	
		@if ($errors->has())
			@foreach($errors->all() as $message)
				<div class='well'>
					{{ $message }}
				</div>
			@endforeach
		@endif
    	
    	
        {{ Form::open(array('action' => 'BestuursController@store', 'class' => 'form-horizontal')) }}

            
            
            @if ($potentialusers != null)
            <div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}" for="user_id">
            	{{ Form::label('create_user_id', trans('bestuur.username'), array('class' => 'col-sm-2 control-label')) }}
            	<div class="col-sm-8 noformfield">
            		{{ Form::select('user_id', $potentialusers ) }}
            	</div>
            	{{-- ($errors->has('user_id') ? $errors->first('user_id') : '') --}}
             	{{ ($errors->has('user_id') ? 'kies een lid' : '') }}

            </div>
            
	        <div class="form-group {{ ($errors->has('bestuursfunctie')) ? 'has-error' : '' }}" for="bestuursfunctie">
	
	            {{ Form::label('edit_bestuursfunctie', trans('bestuur.bestuursfunctie'), array('class' => 'col-sm-2 control-label')) }}
	            <div class="col-sm-8">
	              {{ Form::text('bestuursfunctie', '', array('class' => 'form-control', 'placeholder' => trans('bestuur.bestuursfunctie'), 'id' => 'edit_bestuursfunctie'))}}
	            </div>
	            {{ ($errors->has('bestuursfunctie') ? $errors->first('bestuursfunctie') : '') }}    			
	    	</div>
    	            
            @else
            <div class="form-group">
            	<div class="col-sm-10">Er zijn geen kandidaten</div>
            </div>
            @endif
           
            {{ Form::submit('Aanmaken', array('class' => 'btn btn-primary')) }}
            
        {{ Form::close() }}
    </div>
</div>


@stop