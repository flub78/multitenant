<!-- PersonalAccessToken edit.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
use App\Helpers\DateFormat;
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
    {{__('personal_access_token.elt')}}
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

    <form method="get" action="{{ route('personal_access_token.index' ) }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group mb-2">
        <label class="form-label" for="tokenable_type">{{__("personal_access_token.tokenable_type")}}</label> :

        {{$personal_access_token->accessToken->tokenable::class}}
      </div>

      <div class="form-group mb-2">
        <label class="form-label" for="tokenable_id">{{__("personal_access_token.tokenable_id")}}</label> : {{$personal_access_token->accessToken->tokenable->email}}
      </div>

      <div class="form-group mb-2">
        <label class="form-label" for="name">{{__("personal_access_token.name")}}</label> : {{$personal_access_token->accessToken->tokenable->name}}
      </div>

      <div class="form-group mb-2">
        <label class="form-label" for="token">{{__("personal_access_token.token")}}</label> : {{$personal_access_token->plainTextToken}}
      </div>

      <div class="form-group mb-2">
        <label class="form-label" for="abilities">{{__("personal_access_token.abilities")}}</label> : {{$personal_access_token->accessToken->tokenable->abilities}}
      </div>

      <button type="submit" class="btn btn-primary">{{__('personal_access_token.validate')}}</button>

    </form>
  </div>
</div>
@endsection