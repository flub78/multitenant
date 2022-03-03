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
    {{__('general.edit')}} {{__('code_gen_type.elt')}}
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
    
      <form method="post" action="{{ route('code_gen_type.update', $code_gen_type->id ) }}">
             @csrf
             @method('PATCH')
              
             <div class="form-group">
               <label for="name">{{__("code_gen_type.name")}}</label>
               <input type="text" class="form-control" name="name" value="{{ old("name", $code_gen_type->name) }}"/>
             </div>
           
             <div class="form-group">
               <label for="phone">{{__("code_gen_type.phone")}}</label>
               <input type="text" class="form-control" name="phone" value="{{ old("phone", $code_gen_type->phone) }}"/>
             </div>
           
             <div class="form-group">
               <label for="description">{{__("code_gen_type.description")}}</label>
               <input type="text" class="form-control" name="description" value="{{ old("description", $code_gen_type->description) }}"/>
             </div>
           
             <div class="form-group">
               <label for="year_of_birth">{{__("code_gen_type.year_of_birth")}}</label>
               <input type="text" class="form-control" name="year_of_birth" value="{{ old("year_of_birth", $code_gen_type->year_of_birth) }}"/>
             </div>
           
             <div class="form-group">
               <label for="weight">{{__("code_gen_type.weight")}}</label>
               <input type="text" class="form-control" name="weight" value="{{ old("weight", $code_gen_type->weight) }}"/>
             </div>
           
             <div class="form-group">
               <label for="birthday">{{__("code_gen_type.birthday")}}</label>
               <input type="text" class="form-control datepicker" name="birthday" value="{{ old("birthday", $code_gen_type->birthday) }}"/>
             </div>
           
             <div class="form-group">
               <label for="tea_time">{{__("code_gen_type.tea_time")}}</label>
               <input type="text" class="form-control timepicker" name="tea_time" value="{{ old("tea_time", $code_gen_type->tea_time) }}"/>
             </div>
           
             <div class="form-group">
               <label for="price">{{__("code_gen_type.price")}}</label>
               <input type="text" class="form-control" name="price" value="{{ old("price", $code_gen_type->price) }}"/>
             </div>
           
             <div class="form-group">
               <label for="big_price">{{__("code_gen_type.big_price")}}</label>
               <input type="text" class="form-control" name="big_price" value="{{ old("big_price", $code_gen_type->big_price) }}"/>
             </div>
           
             <div class="form-group">
               <label for="qualifications">{{__("code_gen_type.qualifications")}}</label>
               <input type="text" class="form-control" name="qualifications" value="{{ old("qualifications", $code_gen_type->qualifications) }}"/>
             </div>
           
             <div class="form-group">
               <label for="picture">{{__("code_gen_type.picture")}}</label>
               <input type="text" class="form-control" name="picture" value="{{ old("picture", $code_gen_type->picture) }}"/>
             </div>
           
             <div class="form-group">
               <label for="attachment">{{__("code_gen_type.attachment")}}</label>
               <input type="text" class="form-control" name="attachment" value="{{ old("attachment", $code_gen_type->attachment) }}"/>
             </div>
           
             
             @button_submit({{__('general.update')}})

      </form>
  </div>
</div>
@endsection