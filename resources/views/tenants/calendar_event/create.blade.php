<!-- CalendarEvent create.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
@endphp

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    {{__('calendar_event.new')}}
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
    
      <form method="post" action="{{ route('calendar_event.store') }}">
          @csrf
          
          <div class="form-group">    
             <label for="title">{{__("calendar_event.title")}}</label>
             <input type="text" class="form-control" name="title" value="{{ old("title") }}"/>
          </div>
          
          <div class="form-group">
             <label for="description">{{__("calendar_event.description")}}</label>
             <input type="text" class="form-control" name="description" value="{{ old("description") }}"/>
          </div>
          
           <div class="form-group">
              <label for="allDay"> {{__('calendar_event.allday')}}</label>
              <input type="checkbox" class="form-control" name="allDay" id="allDay" value="1"  {{old('allDay') ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="start_date"> {{__('calendar_event.start_date')}}</label>
              <input type="text" class="form-control datepicker" name="start_date" 
                  value="{{ old('start_date') ? old('start_date') : $start_date }}" 
                  dusk="start_date"/>
           </div>
               
           <div class="form-group">   
              <label for="start_time"> {{__('calendar_event.start_time')}}</label>
              <input type="text" class="form-control timepicker" name="start_time" id="start_time"
              		value="{{ old('start_time') ?  old('start_time') : $start_time}}"/>
          </div>

           <div class="form-group">
              <label for="end_date"> {{__('calendar_event.end_date')}}</label>
              <input type="text" class="form-control datepicker" name="end_date" value="{{ old('end_date') }}" dusk="end_date"/>
              <label for="end_time"> {{__('calendar_event.end_time')}}</label>
              <input type="text" class="form-control timepicker" name="end_time" id="end_time"
              		value="{{ old('end_time') }}"/>
          </div>

           <div class="form-group">
              <label for="backgroundColor"> {{__('calendar_event.background_color')}}</label>
              <input type="color" class="form-control colorpicker " name="backgroundColor" 
                            value="{{ old('backgroundColor') ?  old('backgroundColor') : $defaultBackgroundColor}}"/>              
          </div>

           <div class="form-group">
              <label for="textColor"> {{__('calendar_event.text_color')}}</label>
              <input type="color" class="form-control colorpicker" name="textColor" 
                            value="{{ old('textColor') ?  old('textColor') : $defaultTextColor}}"/>              
          </div>
          
          <button type="submit" class="btn btn-primary"> {{__('calendar_event.add')}}</button>
      </form>
  </div>
</div>
@endsection