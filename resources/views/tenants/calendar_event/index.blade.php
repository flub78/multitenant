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
    <caption>{{__('calendar_event.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('calendar_event.event_title')}}</td>
          <td>{{__('calendar_event.description')}}</td>
          <td>{{__('calendar_event.start_date')}}</td>
          <td>{{__('calendar_event.start_time')}}</td>
          <td>{{__('calendar_event.allday')}}</td>
          <td>{{__('calendar_event.end_date')}}</td>
          <td>{{__('calendar_event.end_time')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($events as $event)
        <tr>
            <td><div style="color:{{$event->textColor}};background-color:{{$event->backgroundColor}}">{{$event->title}}</div></td>
            <td>{{$event->description}}</td>
            <td>{{$event->getStartDate()}}</td>
            <td>{{$event->getStartTime()}}</td>
            <td>
            	<input type="checkbox"   {{($event->allDay) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>{{$event->getEndDate()}}</td>
            <td>{{$event->getEndTime()}}</td>
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
  
    <a href="{{url('calendar')}}/create"><button type="submit" class="btn btn-primary" >{{__('calendar_event.add')}}</button></a> 
</div>  
@endsection


