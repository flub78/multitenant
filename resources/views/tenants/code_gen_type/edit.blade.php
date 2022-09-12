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

    <form method="post" action="{{ route('code_gen_type.update', $code_gen_type->id ) }}" enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="name" value="{{ old("name", $code_gen_type->name) }}" />
        <label class="form-label" for="name">{{__("code_gen_type.name")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="tel" class="form-control" name="phone" value="{{ old("phone", $code_gen_type->phone) }}" />
        <label class="form-label" for="phone">{{__("code_gen_type.phone")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <textarea rows="4" cols="40" class="form-control" name="description">{{ old("description",  $code_gen_type->description) }}</textarea>
        <label class="form-label" for="description">{{__("code_gen_type.description")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="year_of_birth" value="{{ old("year_of_birth", $code_gen_type->year_of_birth) }}" />
        <label class="form-label" for="year_of_birth">{{__("code_gen_type.year_of_birth")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="weight" value="{{ old("weight", $code_gen_type->weight) }}" />
        <label class="form-label" for="weight">{{__("code_gen_type.weight")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="date" class="form-control" name="birthday" value="{{ old("birthday", $code_gen_type->birthday) }}" />
        <label class="form-label" for="birthday">{{__("code_gen_type.birthday")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="time" class="form-control" name="tea_time" value="{{ old("tea_time", $code_gen_type->tea_time) }}" />
        <label class="form-label" for="tea_time">{{__("code_gen_type.tea_time")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="datetime-local" class="form-control" name="takeoff" value="{{ old("takeoff", $code_gen_type->takeoff) }}" />
        <label class="form-label" for="takeoff">{{__("code_gen_type.takeoff")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="price" value="{{ old("price", $code_gen_type->price) }}" />
        <label class="form-label" for="price">{{__("code_gen_type.price")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="big_price" value="{{ old("big_price", $code_gen_type->big_price) }}" />
        <label class="form-label" for="big_price">{{__("code_gen_type.big_price")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        {!! Blade::bitfield_input("code_gen_types", "qualifications", $code_gen_type->qualifications) !!}
        <label class="form-label" for="qualifications">{{__("code_gen_type.qualifications")}}</label>
      </div>

      <div class="form-group mb-2 border">
              <label class="form-label m-2" for="black_and_white">{{__("code_gen_type.black_and_white")}}</label>
              <input type="checkbox" class="form-check-input m-2" name="black_and_white" value="1"  {{old("black_and_white", $code_gen_type->black_and_white) ? 'checked' : ''}}/>
          </div>
           
          <div class="form-floating mb-2 border">
        {!! Blade::select("color_name", $color_name_list, false, $code_gen_type->color_name) !!}
        <label class="form-label" for="color_name">{{__("code_gen_type.color_name")}}</label>
      </div>

      <div class="d-flex flex-wrap">
        <div class="form-floating mb-2 border">
          <input type="file" class="form-control " name="picture" value="{{ old("picture", $code_gen_type->picture) }}" />
          <label class="form-label" for="picture">{{__("code_gen_type.picture")}}</label>
        </div>
        <div class="m-2">
        {!! Blade::picture("code_gen_type.picture", $code_gen_type->id, "picture", $code_gen_type->picture) !!}
        </div>
      </div>

      <div class="d-flex flex-wrap">
        <div class="form-floating mb-2 border">
          <input type="file" class="form-control " name="attachment" value="{{ old("attachment", $code_gen_type->attachment) }}" />
          <label class="form-label" for="attachment">{{__("code_gen_type.attachment")}}</label>
        </div>
        <div class="m-2">
          {{$code_gen_type->attachment}}
        </div>
      </div>

      @button_submit({{__('general.update')}})

    </form>
  </div>
</div>
@endsection