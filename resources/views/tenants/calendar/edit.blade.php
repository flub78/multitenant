<!-- Calendar event edit.blade.php -->

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
     {{__('calendar.edit')}}
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
    
      <form method="post" action="{{ route('calendar.update', $calendarEvent->id ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              
              <label for="title">{{__('calendar.title')}}</label>
              <input type="text" class="form-control" name="title" value="{{ old('title', $calendarEvent->title) }}"/>
          </div>
          
          <div class="form-group">
              <label for="groupId">{{__('calendar.groupId')}}</label>
              <input type="text" class="form-control" name="groupId" value="{{ old('groupId', $calendarEvent->groupId) }}"/>
          </div>

           <div class="form-group">
              <label for="allDay"> {{__('calendar.allday')}}</label>
              <input type="checkbox" class="form-control" name="allDay" value="1"  {{old('allDay', $calendarEvent->allDay) ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="start"> {{__('calendar.start_date')}}</label>
              <input type="text" class="form-control datepicker" name="start" value="{{ old('start', $calendarEvent->getStartDate()) }}"/>
          </div>

           <div class="form-group">
              <label for="start_time"> {{__('calendar.start_time')}}</label>
    		  <!--  {{ old('start_time',$calendarEvent->getStartTime())               }} -->
              <input type="text" class="form-control timepicker" name="start_time" value="{{ old('start_time', $calendarEvent->getStartTime()) }}"/>
          </div>

           <div class="form-group">
              <label for="end"> {{__('calendar.end_date')}}</label>
              <input type="text" class="form-control datepicker" name="end" value="{{ old('end', $calendarEvent->getEndDate()) }}"/>
          </div>

           <div class="form-group">
              <label for="end_time"> {{__('calendar.end_time')}}</label>
    		  <!--  {{ old('end_time',$calendarEvent->getEndTime())               }} -->
              <input type="text" class="form-control timepicker" name="end_time" value="{{ old('end_time', $calendarEvent->getEndTime()) }}"/>
          </div>

           <div class="form-group">
              <label for="backgroundColor"> {{__('calendar.background_color')}}</label>
              <input type="text" class="form-control colorpicker" name="backgroundColor" value="{{ old('backgroundColor') }}"/>
          </div>

           <div class="form-group">
              <label for="textColor"> {{__('calendar.text_color')}}</label>
              <input type="text" class="form-control namedcolorpicker" name="textColor" value="{{ old('textColor') }}"/>
          </div>
          
          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection