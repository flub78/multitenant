<!-- Role create.blade.php -->

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
    {{__('role.new')}}
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
    
      <form method="post" action="{{ route('role.store') }}" enctype="multipart/form-data">
           @csrf
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="name" value="{{ old("name") }}"/>
             <label class="form-label" for="name">{{__("role.name")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="description" value="{{ old("description") }}"/>
             <label class="form-label" for="description">{{__("role.description")}}</label>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection