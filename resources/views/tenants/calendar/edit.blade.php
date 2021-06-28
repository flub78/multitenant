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

          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection