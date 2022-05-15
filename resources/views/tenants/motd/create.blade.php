<!-- Motd create.blade.php -->

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
    {{__('motd.new')}}
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
    
      <form method="post" action="{{ route('motd.store') }}" enctype="multipart/form-data">
           @csrf
           
           <div class="form-group">
             <label for="title">{{__("motd.title")}}</label>
             <input type="text" class="form-control" name="title" value="{{ old("title") }}"/>
           </div>
           
           <div class="form-group">
             <label for="message">{{__("motd.message")}}</label>
             <input type="text" class="form-control" name="message" value="{{ old("message") }}"/>
           </div>
           
           <div class="form-group">
             <label for="publication_date">{{__("motd.publication_date")}}</label>
             <input type="text" class="form-control datepicker" name="publication_date" value="{{ old("publication_date") }}"/>
           </div>
           
           <div class="form-group">
             <label for="end_date">{{__("motd.end_date")}}</label>
             <input type="text" class="form-control datepicker" name="end_date" value="{{ old("end_date") }}"/>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection