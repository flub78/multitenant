<!-- Calendar event create.blade.php -->

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    {{__('calendar.new')}}
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
    
      <form method="post" action="{{ route('calendar.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="title"> {{__('calendar.title')}}</label>
              <input type="text" class="form-control" name="title" value="{{ old('title') }}"/>
          </div>
          
          <div class="form-group">
              <label for="groupId"> {{__('calendar.groupId')}}</label>
              <input type="text" class="form-control" name="groupId" value="{{ old('groupId') }}"/>
          </div>
          
           <div class="form-group">
              <label for="allDay"> {{__('calendar.allday')}}</label>
              <input type="checkbox" class="form-control" name="allDay" value="1"  {{old('allDay') ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="start"> {{__('calendar.start_date')}}</label>
              <input type="text" class="form-control datepicker" name="start" value="{{ old('start') }}"/>
              <label for="start_time"> {{__('calendar.start_time')}}</label>
              <input type="text" class="form-control timepicker" name="start_time" value="{{ old('start_time') }}"/>
          </div>

           <div class="form-group">
              <label for="backgroundColor"> {{__('calendar.background_color')}}</label>
              <input type="text" class="form-control colorpicker" name="backgroundColor" value="{{ old('backgroundColor') }}"/>
          </div>

           <div class="form-group">
              <label for="textColor"> {{__('calendar.text_color')}}</label>
              <input type="text" class="form-control namedcolorpicker" name="textColor" value="{{ old('textColor') }}"/>
          </div>
          
          <button type="submit" class="btn btn-primary"> {{__('calendar.add')}}</button>
      </form>
  </div>
</div>
@endsection