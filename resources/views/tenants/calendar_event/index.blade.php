<!-- index.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
use App\Helpers\DateFormat; 
@endphp

@extends('layouts.app')

@section('content')

<div class="uper d-flex flex-column">

  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  
  @if(session()->get('error'))
    <div class="alert alert-danger">
      {{ session()->get('error') }}  
    </div><br />
  @endif

  <div class="mb-3">
    @button_create({{url('calendar_event')}}, {{__('calendar_event.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('calendar_event.title')}}</caption>
    <thead>
        <tr>
          <td style="width: 30px;"></td>
          <td style="width: 30px;"></td>
          <td>{{__('calendar_event.event_title')}}</td>
          <td>{{__('calendar_event.description')}}</td>
          <td>{{__('calendar_event.start')}}</td>
          <td>{{__('calendar_event.allDay')}}</td>
          <td>{{__('calendar_event.end')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($events as $event)
        <tr>
            <td><a href="{{ route('calendar_event.edit', $event->id)}}" class="btn btn-primary" dusk="edit_{{$event->title}}"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td>
                <form action="{{ route('calendar_event.destroy', $event->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" dusk="delete_{{$event->title}}"><i class="fa-solid fa-trash"></i></button>
                </form>
            </td>
            
            <td><div style="color:{{$event->textColor}};background-color:{{$event->backgroundColor}}">{{$event->title}}</div></td>
            <td>{{$event->description}}</td>
            <td>{{DateFormat::local_datetime($event->start)}}</td>
            <td>
            	<input type="checkbox"   {{($event->allDay) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>{{DateFormat::local_datetime($event->end)}}</td>

        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
  
</div> <!-- content div --> 
@endsection


