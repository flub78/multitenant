<!-- Configuration edit.blade.php -->

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
    {{__('general.edit')}} {{__('configuration.elt')}}
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
    
      <form method="post" action="{{ route('configuration.update', $configuration->key ) }}">
              @csrf
              @method('PATCH')
              
             <div class="form-group">
               <label for="key">{{__("configuration.key")}}</label>
               {!! Blade::select("key", $key_list, false, $configuration->key) !!}
             </div>
           
             <div class="form-group">
               <label for="value">{{__("configuration.value")}}</label>
               <input type="text" class="form-control" name="value" value="{{ old("value", $configuration->value) }}"/>
             </div>
           
             
             @button_submit({{__('general.update')}})

      </form>
  </div>
</div>
@endsection