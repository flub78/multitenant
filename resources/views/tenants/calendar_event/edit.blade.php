<!-- CalendarEvent edit.blade.php -->

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
    {{__('general.edit')}} {{__('calendar_event.elt')}}
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
    
      <form method="post" action="{{ route('calendar_event.update', $calendar_event->id ) }}" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
              
             <div class="form-group mb-2">
               <label class="form-label" for="title">{{__("calendar_event.title")}}</label>
              <input type="text" class="form-control" name="title" value="{{ old("title", $calendar_event->title) }}"/>
          </div>
          
             <div class="form-group mb-2">
               <label class="form-label" for="description">{{__("calendar_event.description")}}</label>
              <input type="text" class="form-control" name="description" value="{{ old("description", $calendar_event->description) }}"/>
          </div>

             <div class="form-group mb-2">
               <label class="form-label" for="allDay">{{__("calendar_event.allDay")}}</label>
               <input type="checkbox" class="form-check-input" name="allDay" value="1"  {{old("allDay", $calendar_event->allDay) ? 'checked' : ''}}/>
          </div>
          
             <div class="form-group mb-2">
               <label class="form-label" for="start">{{__("calendar_event.start")}}</label>
               <input type="datetime-local" class="form-control" name="start" value="{{ old("start", $calendar_event->start) }}"/>
          </div>

             <div class="form-group mb-2">
               <label class="form-label" for="end">{{__("calendar_event.end")}}</label>
               <input type="datetime-local" class="form-control" name="end" value="{{ old("end", $calendar_event->end) }}"/>
          </div>

             <div class="form-group mb-2">
               <label class="form-label" for="backgroundColor">{{__("calendar_event.backgroundColor")}}</label>
               <input type="color" class="form-control" name="backgroundColor" value="{{ old("backgroundColor", $calendar_event->backgroundColor) }}"/>
          </div>

             <div class="form-group mb-2">
               <label class="form-label" for="textColor">{{__("calendar_event.textColor")}}</label>
               <input type="color" class="form-control" name="textColor" value="{{ old("textColor", $calendar_event->textColor) }}"/>
          </div>
          
             
             @button_submit({{__('general.update')}})

       </form>
  </div>
</div>
@endsection