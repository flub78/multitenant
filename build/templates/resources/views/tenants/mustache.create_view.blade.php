{{=[[ ]]=}}
<!-- [[element]]_create.blade.php -->

@extends('master') 
@section('header_includes') 
	@parent 
@endsection

@section('content')
<h2>@lang('[[element]].create_title')</h2>
<br />

@if ($errors->any()) 

	<ul id ="errors" class="alert alert-danger" > 
		@foreach ($errors->all() as $error) 
			<li> {{ $error }} </li > 
		@endforeach 
	</ul > 
@endif

    {{ Form::open(array('action' => '[[class_name]]Controller@store', 'files' => true)) }}

	@csrf
	
	[[#fillable]]
	[[& field_input]]
		    
	[[/fillable]]
	
	{!! Form::wa_submit_cancel(__('general.submit'), __('general.cancel')) !!}
	
	{{ Form::close() }}

@endsection 

@section('footerScripts') 
	@parent
<script type="text/javascript">

	$(function(){  		
		$.datepicker.setDefaults(
           $.extend(
              {'dateFormat': '@lang('general.datepicker_format')'},
              $.datepicker.regional['fr']
           )
          
		);  		
        $( ".datepicker" ).datepicker({ changeYear: true });
    }); 
</script>
@endsection


