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
    
      <form method="post" action="{{ route('user_role.store') }}" enctype="multipart/form-data">
           @csrf
           
           <div class="form-floating mb-2 border">
             {!! Blade::selector("user_id", $user_list, "") !!}
             <label class="form-label" for="user_id">{{__("user_role.user_id")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             {!! Blade::selector("role_id", $role_list, "") !!}
             <label class="form-label" for="role_id">{{__("user_role.role_id")}}</label>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection