<!-- Profile create.blade.php -->

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
    {{__('profile.new')}}
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
    
      <form method="post" action="{{ route('profile.store') }}" enctype="multipart/form-data">
           @csrf
           
           <div class="form-group">
             <label for="first_name">{{__("profile.first_name")}}</label>
             <input type="text" class="form-control" name="first_name" value="{{ old("first_name") }}"/>
           </div>
           
           <div class="form-group">
             <label for="last_name">{{__("profile.last_name")}}</label>
             <input type="text" class="form-control" name="last_name" value="{{ old("last_name") }}"/>
           </div>
           
           <div class="form-group">
             <label for="birthday">{{__("profile.birthday")}}</label>
             <input type="text" class="form-control datepicker" name="birthday" value="{{ old("birthday") }}"/>
           </div>
           
           <div class="form-group">
             <label for="user_id">{{__("profile.user_id")}}</label>
             {!! Blade::selector("user_id", $user_list, "") !!}
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection