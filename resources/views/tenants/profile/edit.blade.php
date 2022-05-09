<!-- Profile edit.blade.php -->

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
    {{__('general.edit')}} {{__('profile.elt')}}
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
    
      <form method="post" action="{{ route('profile.update', $profile->id ) }}" enctype="multipart/form-data">
             @csrf
             @method('PATCH')
              
             <div class="form-group">
               <label for="first_name">{{__("profile.first_name")}}</label>
               <input type="text" class="form-control" name="first_name" value="{{ old("first_name", $profile->first_name) }}"/>
             </div>
           
             <div class="form-group">
               <label for="last_name">{{__("profile.last_name")}}</label>
               <input type="text" class="form-control" name="last_name" value="{{ old("last_name", $profile->last_name) }}"/>
             </div>
           
             <div class="form-group">
               <label for="birthday">{{__("profile.birthday")}}</label>
               <input type="text" class="form-control datepicker" name="birthday" value="{{ old("birthday", $profile->birthday) }}"/>
             </div>
           
             <div class="form-group">
               <label for="user_id">{{__("profile.user_id")}}</label>
               {!! Blade::selector("user_id", $user_list, $profile->user_id) !!}
             </div>
           
             
             @button_submit({{__('general.update')}})

      </form>
  </div>
</div>
@endsection