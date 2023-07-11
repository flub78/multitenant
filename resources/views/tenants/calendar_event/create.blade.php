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
    
      <form method="post" action="{{ route('calendar_event.store') }}" enctype="multipart/form-data">
          @csrf
          
           <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="title" value="{{ old("title") }}"/>
             <label class="form-label" for="title">{{__("calendar_event.title")}}</label>
          </div>
          
           <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="description" value="{{ old("description") }}"/>
             <label class="form-label" for="description">{{__("calendar_event.description")}}</label>
          </div>
          
           <div class="form-group mb-2 border">
              <label class="form-label m-2" for="allDay">{{__("calendar_event.allDay")}}</label>
              <input type="checkbox" class="form-check-input m-2" name="allDay" id="allDay" value="1"  {{old("allDay") ? 'checked' : ''}}/>
          </div>
          
           <div class="form-floating mb-2 border">
              <input type="datetime-local" class="form-control" name="start" value="{{ old("start") }}"/>
             <label class="form-label" for="start">{{__("calendar_event.start")}}</label>
           </div>
               
           <div class="form-floating mb-2 border">
              <input type="datetime-local" class="form-control" name="end" value="{{ old("end") }}"/>
             <label class="form-label" for="end">{{__("calendar_event.end")}}</label>
          </div>

           <div class="form-floating mb-2 border">
            @if (old("backgroundColor"))
            <input type="color" class="form-control" name="backgroundColor" 
              value="{{ old("backgroundColor")  }}"/>
            @else
            <input type="color" class="form-control" name="backgroundColor" 
              value="{{ $defaultBackgroundColor }}"/> 
            @endif

             <label class="form-label" for="backgroundColor">{{__("calendar_event.backgroundColor")}}</label>
          </div>

           <div class="form-floating mb-2 border">
 
              @if (old("textColor"))
              <input type="color" class="form-control" name="textColor" 
                value="{{ old("textColor")  }}"/>
              @else
              <input type="color" class="form-control" name="textColor" 
                value="{{ $defaultTextColor }}"/>
              @endif

             <label class="form-label" for="textColor">{{__("calendar_event.textColor")}}</label>
          </div>
          
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection