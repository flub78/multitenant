<!-- index.blade.php -->

@extends('layouts.app')

@section('content')


<div class="uper">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  <table class="table table-striped"  id="maintable">
    <caption>Users</caption>
    <thead>
        <tr>
          <td>Title</td>
          <td>Group Id</td>
          <td>Start date</td>
          <td>All day</td>
          <td >Edit</td>
          <td >Delete</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{$event->name}}</td>
            <td>{{$event->email}}</td>
            <td>
            	<input type="checkbox"   {{($event->admin) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>
            	<input type="checkbox"   {{($event->active) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td><a href="{{ route('events.edit', $event->id)}}" class="btn btn-primary">Edit</a></td>
            
            <td>
                <form action="{{ route('events.destroy', $event->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('calendar')}}/create"><button type="submit" class="btn btn-primary" >@lang('general.create') @lang('events.element')</button></a> 
</div>  
@endsection


