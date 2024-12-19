<!-- Attachment edit.blade.php -->

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
    {{__('attachment.elt')}}
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
    
      <form method="post" action="{{ route('attachment.update', $attachment->id ) }}" enctype="multipart/form-data">
             @csrf
             @method('PATCH')
              
             <div class="form-group mb-2">
               <label class="form-label" for="referenced_table">{{__("attachment.referenced_table")}}</label> : {{$attachment->referenced_table}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="referenced_id">{{__("attachment.referenced_id")}}</label> : {{$attachment->referenced_id}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="user_id">{{__("attachment.user_id")}}</label> : {{$attachment->user_id}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="description">{{__("attachment.description")}}</label> : {{$attachment->description}}
             </div>
           
             <div class="form-group mb-2">
               <label class="form-label" for="file">{{__("attachment.file")}}</label> : {!! Blade::download("attachment.file", $attachment->id, "file", $attachment->file, "attachment.file") !!}
             </div>
           
             
             <a href="{{ route('attachment.edit', $attachment->id) }}" class="btn btn-primary" dusk="edit_{{ $attachment->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  <form action="{{ route("attachment.destroy", $attachment->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $attachment->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>

             
      </form>
  </div>
</div>
@endsection