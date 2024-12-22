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



<div class="d-flex flex-column">

  <div class="card uper">
    <div class="card-header">
      {{__('general.edit')}} {{__('calendar_event.elt')}}
    </div>
    <div class="card-body d-flex flex-column">
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div><br />
      @endif
      <div class="d-flex flex-row flex-wrap justify-content-evenly">

        <div class="d-flex flex-column">

          <form method="post" action="{{ route('calendar_event.update', $calendar_event->id ) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="title" value="{{ old("title", $calendar_event->title) }}" />
              <label class="form-label" for="title">{{__("calendar_event.title")}}</label>
            </div>

            <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="description" value="{{ old("description", $calendar_event->description) }}" />
              <label class="form-label" for="description">{{__("calendar_event.description")}}</label>
            </div>

            <div class="form-group mb-2 border">
              <label class="form-label m-2" for="allDay">{{__("calendar_event.allDay")}}</label>
              <input type="checkbox" class="form-check-input m-2" name="allDay" value="1" {{old("allDay", $calendar_event->allDay) ? 'checked' : ''}} />
            </div>

            <div class="form-floating mb-2 border">
              <input type="datetime-local" class="form-control" name="start" value="{{ old("start", $calendar_event->start) }}" />
              <label class="form-label" for="start">{{__("calendar_event.start")}}</label>
            </div>

            <div class="form-floating mb-2 border">
              <input type="datetime-local" class="form-control" name="end" value="{{ old("end", $calendar_event->end) }}" />
              <label class="form-label" for="end">{{__("calendar_event.end")}}</label>
            </div>

            <div class="form-floating mb-2 border">
              <input type="color" class="form-control" name="backgroundColor" value="{{ old("backgroundColor", $calendar_event->backgroundColor) }}" />
              <label class="form-label" for="backgroundColor">{{__("calendar_event.backgroundColor")}}</label>
            </div>

            <div class="form-floating mb-2 border">
              <input type="color" class="form-control" name="textColor" value="{{ old("textColor", $calendar_event->textColor) }}" />
              <label class="form-label" for="textColor">{{__("calendar_event.textColor")}}</label>
            </div>


            @button_submit({{__('general.update')}})

          </form>

        </div>

        <div>

          <input type="hidden" name="referenced_table" value="calendar_events">
          <input type="hidden" name="referenced_id" value="{{ $calendar_event->id }}">

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
              <a href="{{ route('attachment.create', ['referenced_table' => 'calendar_events', 'referenced_id' => $calendar_event->id]) }}"
                class="btn btn-primary">{{ __('attachment.add') }}</a>

            </div>

            <div class="container-fluid mb-3">
              <table class="table table-striped" id="attachment_table">
                <caption>{{__('attachment.title')}}</caption>
                <thead>
                  <tr>
                    <th style="width: 30px;"></th>
                    <th style="width: 30px;"></th>


                    <th> {{__('attachment.filename')}} </th>
                    <th> {{__('attachment.description')}} </th>
                    <th class="text-center"> {{__('attachment.file')}} </th>
                  </tr>
                </thead>

                <tbody>
                  @foreach($attachments as $attachment)
                  <tr>
                    <td> <a href="{{ route('attachment.edit', $attachment->id) }}" class="btn btn-primary" dusk="edit_{{ $attachment->id }}"><i class="fa-solid fa-pen-to-square"></i></a> </td>
                    <td>
                      <form action="{{ route("attachment.destroy", $attachment->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit" dusk="delete_{{ $attachment->id }}"><i class="fa-solid fa-trash"></i></button>
                      </form>
                    </td>

                    <td> {{$attachment->filename}}</td>
                    <td> {{$attachment->description}}</td>
                    <td class="text-center"> {!! Blade::attachment("attachment.file", $attachment->id, "file", $attachment->file, "File") !!}</td>

                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div> <!-- content div -->
        </div>
      </div>
    </div>
  </div>



</div>
@endsection