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
    
      <form method="post" action="{{ route('user.store') }}">
           @csrf
           
           <div class="form-group">
             <label for="name">{{__("user.name")}}</label>
             <input type="text" class="form-control" name="name" value="{{ old("name") }}"/>
           </div>
           
           <div class="form-group">
             <label for="email">{{__("user.email")}}</label>
             <input type="text" class="form-control" name="email" value="{{ old("email") }}"/>
           </div>
           
           <div class="form-group">
             <label for="password">{{__("user.password")}}</label>
             <input type="password" class="form-control" name="password" value="{{ old("password") }}"/>
           </div>
           
           <div class="form-group">
             <label for="password_confirmation">{{__("user.password_confirmation")}}</label>
             <input type="password" class="form-control" name="password_confirmation" value="{{ old("password_confirmation") }}"/>
           </div>
           
           <div class="form-group">
             <label for="admin">{{__("user.admin")}}</label>
             <input type="checkbox" class="form-control" name="admin" value="1"  {{old('admin') ? 'checked' : ''}}/>
           </div>
           
           <div class="form-group">
             <label for="active">{{__("user.active")}}</label>
             <input type="checkbox" class="form-control" name="active" value="1"  {{old('active') ? 'checked' : ''}}/>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection