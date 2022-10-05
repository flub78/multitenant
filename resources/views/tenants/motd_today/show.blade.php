<!-- MotdToday edit.blade.php -->

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
    {{__('motd_today.elt')}}
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
    
      <form method="post" action="{{ route('motd_today.update', $motd_today-> ) }}" enctype="multipart/form-data">
             @csrf
             @method('PATCH')
              
             <div class="form-group mb-2">
               <label class="form-label" for="title">{{__("motd_today.title")}}</label> : {{$motd_today->title}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="message">{{__("motd_today.message")}}</label> : {{$motd_today->message}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="publication_date">{{__("motd_today.publication_date")}}</label> : {{DateFormat::to_local_date($motd_today->publication_date)}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="end_date">{{__("motd_today.end_date")}}</label> : {{DateFormat::to_local_date($motd_today->end_date)}}
             </div>
           
             
             <a href="{{ route('motd_today.edit', $motd_today->) }}" class="btn btn-primary" dusk="edit_{{ $motd_today-> }}"><i class="fa-solid fa-pen-to-square"></i></a>  <form action="{{ route("motd_today.destroy", $motd_today->)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $motd_today-> }}"><i class="fa-solid fa-trash"></i></button>
                 </form>

             
      </form>
  </div>
</div>
@endsection