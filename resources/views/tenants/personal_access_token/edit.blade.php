<!-- PersonalAccessToken edit.blade.php -->

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
    {{__('general.edit')}} {{__('personal_access_token.elt')}}
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
    
      <form method="post" action="{{ route('personal_access_token.update', $personal_access_token->id ) }}" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
              
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="tokenable_type" value="{{ old("tokenable_type", $personal_access_token->tokenable_type) }}"/>
              <label class="form-label" for="tokenable_type">{{__("personal_access_token.tokenable_type")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="tokenable_id" value="{{ old("tokenable_id", $personal_access_token->tokenable_id) }}"/>
              <label class="form-label" for="tokenable_id">{{__("personal_access_token.tokenable_id")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="name" value="{{ old("name", $personal_access_token->name) }}"/>
              <label class="form-label" for="name">{{__("personal_access_token.name")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="token" value="{{ old("token", $personal_access_token->token) }}"/>
              <label class="form-label" for="token">{{__("personal_access_token.token")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <textarea rows="4" cols="40" class="form-control" name="abilities">{{ old("abilities",  $personal_access_token->abilities) }}</textarea>
              <label class="form-label" for="abilities">{{__("personal_access_token.abilities")}}</label>
          </div>
           
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="last_used_at" value="{{ old("last_used_at", $personal_access_token->last_used_at) }}"/>
              <label class="form-label" for="last_used_at">{{__("personal_access_token.last_used_at")}}</label>
          </div>
           
             
          @button_submit({{__('general.update')}})

      </form>
  </div>
</div>
@endsection