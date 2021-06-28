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
    <caption>{{__('calendar.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('calendar.event_title')}}</td>
          <td>{{__('calendar.groupId')}}</td>
          <td>{{__('calendar.start_date')}}</td>
          <td>{{__('calendar.allday')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{$event->title}}</td>
            <td>{{$event->groupId}}</td>
            <td>{{$event->start}}</td>
            <td>
            	<input type="checkbox"   {{($event->allday) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td><a href="{{ route('calendar.edit', $event->id)}}" class="btn btn-primary">{{__('general.edit')}}</a></td>
            
            <td>
                <form action="{{ route('calendar.destroy', $event->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('calendar')}}/create"><button type="submit" class="btn btn-primary" >{{__('calendar.add')}}</button></a> 
</div>  
@endsection


