<!-- User create.blade.php -->

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
    {{__('user.new')}}
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
    
      <form method="post" action="{{ route('user.store') }}" enctype="multipart/form-data">
           @csrf
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="name" value="{{ old("name") }}"/>
              <label class="form-label" for="name">{{__("user.name")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="email" value="{{ old("email") }}"/>
              <label class="form-label" for="email">{{__("user.email")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="password" class="form-control" name="password" value="{{ old("password") }}"/>
              <label class="form-label" for="password">{{__("user.password")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="password" class="form-control" name="password_confirmation" value="{{ old("password_confirmation") }}"/>
              <label class="form-label" for="password_confirmation">{{__("user.password_confirmation")}}</label>
           </div>
           
           <div class="form-group mb-2 border">
              <label class="form-label m-2" for="admin">{{__("user.admin")}}</label>
              <input type="checkbox" class="form-check-input m-2" name="admin" id="admin" value="1"  {{old("admin") ? 'checked' : ''}}/>
           </div>
           
           <div class="form-group mb-2 border">
              <label class="form-label m-2" for="active">{{__("user.active")}}</label>
              <input type="checkbox" class="form-check-input m-2" name="active" id="active" value="1"  {{old("active") ? 'checked' : ''}}/>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection