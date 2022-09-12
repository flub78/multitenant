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
    
      <form method="post" action="{{ route('configuration.update', $configuration->key ) }}" enctype="multipart/form-data">
              @csrf
              @method('PATCH')
              
             <div class="form-group  pb-3">
               <label for="key">{{__("configuration.key")}}</label>
               {!! Blade::select("key", $key_list, false, $configuration->key) !!}
             </div>
           
          <div class="form-floating mb-2 border">
               <input type="text" class="form-control" name="value" value="{{ old("value", $configuration->value) }}"/>
              <label class="form-label" for="value">{{__("configuration.value")}}</label>
             </div>
           
             
             @button_submit({{__('general.update')}})

      </form>
  </div>
</div>
@endsection