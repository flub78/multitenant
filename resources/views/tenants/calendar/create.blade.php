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
    New event
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
              
              <label for="title">Title</label>
              <input type="text" class="form-control" name="title" value="{{ old('title') }}"/>
          </div>
          
          <div class="form-group">
              <label for="groupId">Group Id</label>
              <input type="text" class="form-control" name="groupId" value="{{ old('groupId') }}"/>
          </div>
          
           <div class="form-group">
              <label for="allDay">All day</label>
              <input type="checkbox" class="form-control" name="allDay" value="1"  {{old('allDay') ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="start">Start Day</label>
              <input type="text" class="form-control datepicker" name="start" value="{{ old('start') }}"/>
          </div>

           <div class="form-group">
              <label for="starttime">Start Time</label>
              <input type="text" class="form-control timepicker" name="starttime" value="{{ old('starttime') }}"/>
          </div>

           <div class="form-group">
              <label for="backgroundColor">Background Color</label>
              <input type="text" class="form-control colorpicker" name="backgroundColor" value="{{ old('backgroundColor') }}"/>
          </div>

           <div class="form-group">
              <label for="textColor">Text Color</label>
              <input type="text" class="form-control namedcolorpicker" name="textColor" value="{{ old('textColor') }}"/>
          </div>
          
          <button type="submit" class="btn btn-primary">Add Event</button>
      </form>
  </div>
</div>
@endsection