<!-- Users create.blade.php -->
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
              {!! Blade::label($for="name", $label=__('user.name')) !!}
              {!! Blade::text_create("name", old('name'))!!}
          </div>
          
          <div class="form-group">
              {!! Blade::label($for="email", $label=__('user.email')) !!}
              {!! Blade::email_create("email", old('email'))!!}
          </div>
          
           <div class="form-group">
              <label for="cases">{{__('user.admin')}}</label>
              <input type="checkbox" class="form-control" name="admin" value="1"  {{old('admin') ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="cases">{{__('user.active')}}</label>
              <input type="checkbox" class="form-control" name="active" value="1"  {{old('active') ? 'checked' : ''}}/>
          </div>
          
          <div class="form-group">
              <label for="cases">{{__('user.password')}}</label>
              <input type="password" class="form-control" name="password" value="{{ old('password') }}"/>
          </div>

          <div class="form-group">
              <label for="cases">{{__('user.confirm')}}</label>
              <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}"/>
          </div>

          <button type="submit" class="btn btn-primary">{{__('general.submit')}}</button>
      </form>
  </div>
</div>
@endsection