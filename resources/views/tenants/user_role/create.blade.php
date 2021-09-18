<!-- Users create.blade.php -->

@extends('layouts.app')

@section('content')

@php
use App\Helpers\HtmlHelper as HTML;
use App\Helpers\BladeHelper as Blade;
@endphp

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    {{__('user_roles.new')}}
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
    
      <form method="post" action="{{ route('user_role.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="user_id">{{__('user_roles.user_id')}}</label>
              
              {!! Blade::selector("user_id", $user_list, "") !!}

              <label for="role_id">{{__('user_roles.role_id')}}</label>
              {!! Blade::selector("role_id", $role_list, "") !!}
          </div>
          
          <button type="submit" class="btn btn-primary">{{__('general.submit')}}</button>
      </form>
  </div>
</div>
@endsection