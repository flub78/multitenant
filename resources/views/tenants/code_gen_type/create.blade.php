<!-- CodeGenType create.blade.php -->

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
    {{__('code_gen_type.new')}}
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
    
      <form method="post" action="{{ route('code_gen_type.store') }}">
           @csrf
           
           <div class="form-group">
             <label for="name">{{__("code_gen_type.name")}}</label>
             <input type="text" class="form-control" name="name" value="{{ old("name") }}"/>
           </div>
           
           <div class="form-group">
             <label for="phone">{{__("code_gen_type.phone")}}</label>
             <input type="text" class="form-control" name="phone" value="{{ old("phone") }}"/>
           </div>
           
           <div class="form-group">
             <label for="description">{{__("code_gen_type.description")}}</label>
             <input type="text" class="form-control" name="description" value="{{ old("description") }}"/>
           </div>
           
           <div class="form-group">
             <label for="year_of_birth">{{__("code_gen_type.year_of_birth")}}</label>
             <input type="text" class="form-control" name="year_of_birth" value="{{ old("year_of_birth") }}"/>
           </div>
           
           <div class="form-group">
             <label for="weight">{{__("code_gen_type.weight")}}</label>
             <input type="text" class="form-control" name="weight" value="{{ old("weight") }}"/>
           </div>
           
           <div class="form-group">
             <label for="birthday">{{__("code_gen_type.birthday")}}</label>
             <input type="text" class="form-control datepicker" name="birthday" value="{{ old("birthday") }}"/>
           </div>
           
           <div class="form-group">
             <label for="tea_time">{{__("code_gen_type.tea_time")}}</label>
             <input type="text" class="form-control timepicker" name="tea_time" value="{{ old("tea_time") }}"/>
           </div>
           
           <div class="form-group">
             <label for="takeoff_date">{{__("code_gen_type.takeoff_date")}}</label>
             <input type="text" class="form-control datepicker" name="takeoff_date" value="{{ old("takeoff_date") }}"/>
           </div>
           
           <div class="form-group">
             <label for="takeoff_time">{{__("code_gen_type.takeoff_time")}}</label>
             <input type="text" class="form-control timepicker" name="takeoff_time" value="{{ old("takeoff_time") }}"/>
           </div>
           
           <div class="form-group">
             <label for="price">{{__("code_gen_type.price")}}</label>
             <input type="text" class="form-control" name="price" value="{{ old("price") }}"/>
           </div>
           
           <div class="form-group">
             <label for="big_price">{{__("code_gen_type.big_price")}}</label>
             <input type="text" class="form-control" name="big_price" value="{{ old("big_price") }}"/>
           </div>
           
           <div class="form-group">
             <label for="qualifications">{{__("code_gen_type.qualifications")}}</label>
             <input type="text" class="form-control" name="qualifications" value="{{ old("qualifications") }}"/>
           </div>
           
           <div class="form-group">
             <label for="picture">{{__("code_gen_type.picture")}}</label>
             <input type="text" class="form-control" name="picture" value="{{ old("picture") }}"/>
           </div>
           
           <div class="form-group">
             <label for="attachment">{{__("code_gen_type.attachment")}}</label>
             <input type="text" class="form-control" name="attachment" value="{{ old("attachment") }}"/>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection