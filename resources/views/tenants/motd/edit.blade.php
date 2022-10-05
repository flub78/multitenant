<!-- Motd edit.blade.php -->

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
    {{__('general.edit')}} {{__('motd.elt')}}
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
    
      <form method="post" action="{{ route('motd.update', $motd->id ) }}" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
              
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="title" value="{{ old("title", $motd->title) }}"/>
              <label class="form-label" for="title">{{__("motd.title")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="message" value="{{ old("message", $motd->message) }}"/>
              <label class="form-label" for="message">{{__("motd.message")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="date" class="form-control" name="publication_date" value="{{ old("publication_date", $motd->publication_date) }}"/>
              <label class="form-label" for="publication_date">{{__("motd.publication_date")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="date" class="form-control" name="end_date" value="{{ old("end_date", $motd->end_date) }}"/>
              <label class="form-label" for="end_date">{{__("motd.end_date")}}</label>
          </div>
           
             
          @button_submit({{__('general.update')}})

      </form>
  </div>
</div>
@endsection