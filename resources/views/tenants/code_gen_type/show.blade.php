<!-- CodeGenType edit.blade.php -->

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
    {{__('code_gen_type.elt')}}
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
    
      <form method="post" action="{{ route('code_gen_type.update', $code_gen_type->id ) }}" enctype="multipart/form-data">
             @csrf
             @method('PATCH')
              
             <div class="form-group">
               <label for="name">{{__("code_gen_type.name")}}</label> : {{$code_gen_type->name}}
             </div>
           
             <div class="form-group">
               <label for="phone">{{__("code_gen_type.phone")}}</label> : {{$code_gen_type->phone}}
             </div>
           
             <div class="form-group">
               <label for="description">{{__("code_gen_type.description")}}</label> : {{$code_gen_type->description}}
             </div>
           
             <div class="form-group">
               <label for="year_of_birth">{{__("code_gen_type.year_of_birth")}}</label> : {{$code_gen_type->year_of_birth}}
             </div>
           
             <div class="form-group">
               <label for="weight">{{__("code_gen_type.weight")}}</label> : <div align="right">{!! Blade::float($code_gen_type->weight) !!}</div>
             </div>
           
             <div class="form-group">
               <label for="birthday">{{__("code_gen_type.birthday")}}</label> : {{$code_gen_type->birthday}}
             </div>
           
             <div class="form-group">
               <label for="tea_time">{{__("code_gen_type.tea_time")}}</label> : {{$code_gen_type->tea_time}}
             </div>
           
             <div class="form-group">
               <label for="takeoff_date">{{__("code_gen_type.takeoff_date")}}</label> : {{$code_gen_type->takeoff_date}}
             </div>
           
             <div class="form-group">
               <label for="takeoff_time">{{__("code_gen_type.takeoff_time")}}</label> : {{$code_gen_type->takeoff_time}}
             </div>
           
             <div class="form-group">
               <label for="price">{{__("code_gen_type.price")}}</label> : <div align="right">{!! Blade::float($code_gen_type->price) !!}</div>
             </div>
           
             <div class="form-group">
               <label for="big_price">{{__("code_gen_type.big_price")}}</label> : <div align="right">{!! Blade::currency($code_gen_type->big_price) !!}</div>
             </div>
           
             <div class="form-group">
               <label for="qualifications">{{__("code_gen_type.qualifications")}}</label> : {!! Blade::bitfield("code_gen_types", "qualifications", $code_gen_type->qualifications) !!}
             </div>
           
             <div class="form-group">
               <label for="color_name">{{__("code_gen_type.color_name")}}</label> : {!! Blade::enumerate("code_gen_type.color_name", $code_gen_type->color_name) !!}
             </div>
           
             <div class="form-group">
               <label for="picture">{{__("code_gen_type.picture")}}</label> : {!! Blade::picture("code_gen_type.picture", $code_gen_type->id, "picture", $code_gen_type->picture) !!}
             </div>
           
             <div class="form-group">
               <label for="attachment">{{__("code_gen_type.attachment")}}</label> : {!! Blade::download("code_gen_type.file", $code_gen_type->id, "attachment", $code_gen_type->attachment, "Attachment") !!}
             </div>
           
             
             <a href="{{ route('code_gen_type.edit', $code_gen_type->id) }}" class="btn btn-primary" dusk="edit_{{ $code_gen_type->id }}">{{ __('general.edit') }}</a>  <form action="{{ route("code_gen_type.destroy", $code_gen_type->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $code_gen_type->id }}">{{__('general.delete')}}</button>
                 </form>

             
      </form>
  </div>
</div>
@endsection