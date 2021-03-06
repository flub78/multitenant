<!-- Configuration create.blade.php -->

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
    {{__('configuration.new')}}
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
    
      <form method="post" action="{{ route('configuration.store') }}">
           @csrf
           
           <div class="form-group">
             <label for="key">{{__("configuration.key")}}</label>
             <select class="form-select" name="key" id="key">
			    <option value="app.locale">app.locale</option>
			    <option value="app.timezone">app.timezone</option>
			    <option value="browser.locale">browser.locale</option>
			</select>

           </div>
           
           <div class="form-group">
             <label for="value">{{__("configuration.value")}}</label>
             <input type="text" class="form-control" name="value" value="{{ old("value") }}"/>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection