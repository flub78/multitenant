<!-- PersonalAccessToken create.blade.php -->

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
    {{__('personal_access_token.new')}}
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

    <form method="post" action="{{ route('personal_access_token.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="tokenable_id" value="{{ auth()->user()->email }}" readonly />
        <label class="form-label" for="tokenable_id">{{__("personal_access_token.tokenable_id")}}</label>
      </div>
      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="name" value="{{ old("name") }}" />
        <label class="form-label" for="name">{{__("personal_access_token.name")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <textarea rows="4" cols="40" class="form-control" name="abilities">{{ old("abilities") }}</textarea>
        <label class="form-label" for="abilities">{{__("personal_access_token.abilities")}}</label>
      </div>

      @button_submit({{__('general.submit')}})

    </form>
  </div>
</div>
@endsection