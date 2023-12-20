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
    
      <form method="post" action="{{ route('personal_access_token.update', $personal_access_token->id ) }}" enctype="multipart/form-data">
             @csrf
             @method('PATCH')
              
             <div class="form-group mb-2">
               <label class="form-label" for="tokenable_type">{{__("personal_access_token.tokenable_type")}}</label> : {{$personal_access_token->tokenable_type}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="tokenable_id">{{__("personal_access_token.tokenable_id")}}</label> : {{$personal_access_token->tokenable_id}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="name">{{__("personal_access_token.name")}}</label> : {{$personal_access_token->name}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="token">{{__("personal_access_token.token")}}</label> : {{$personal_access_token->token}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="abilities">{{__("personal_access_token.abilities")}}</label> : {{$personal_access_token->abilities}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="last_used_at">{{__("personal_access_token.last_used_at")}}</label> : {{$personal_access_token->last_used_at}}
             </div>
           
             
             <a href="{{ route('personal_access_token.edit', $personal_access_token->id) }}" class="btn btn-primary" dusk="edit_{{ $personal_access_token->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  <form action="{{ route("personal_access_token.destroy", $personal_access_token->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $personal_access_token->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>

             
      </form>
  </div>
</div>
@endsection