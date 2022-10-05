<!-- Motd edit.blade.php -->

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
    {{__('motd.elt')}}
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
    
      <form method="post" action="{{ route('motd.update', $motd->id ) }}" enctype="multipart/form-data">
             @csrf
             @method('PATCH')
              
             <div class="form-group mb-2">
               <label class="form-label" for="title">{{__("motd.title")}}</label> : {{$motd->title}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="message">{{__("motd.message")}}</label> : {{$motd->message}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="publication_date">{{__("motd.publication_date")}}</label> : {{DateFormat::to_local_date($motd->publication_date)}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="end_date">{{__("motd.end_date")}}</label> : {{DateFormat::to_local_date($motd->end_date)}}
             </div>
           
             
             <a href="{{ route('motd.edit', $motd->id) }}" class="btn btn-primary" dusk="edit_{{ $motd->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  <form action="{{ route("motd.destroy", $motd->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $motd->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>

             
      </form>
  </div>
</div>
@endsection