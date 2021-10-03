<!-- UserRole create.blade.php -->

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
    {{__('user_role.new')}}
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
           @csrf
           
           <div class="form-group">
             <label for="user_id">{{__("user_role.user_id")}}</label>
             {!! Blade::selector("user_id", $user_list, "") !!}
           </div>
           
           <div class="form-group">
             <label for="role_id">{{__("user_role.role_id")}}</label>
             {!! Blade::selector("role_id", $role_list, "") !!}
           </div>
           
           
           @button_submit()

      </form>
  </div>
</div>
@endsection