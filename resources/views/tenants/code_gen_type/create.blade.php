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
    
      <form method="post" action="{{ route('code_gen_type.store') }}" enctype="multipart/form-data">
           @csrf
           
           <!--
           For floating label the label must come after the input and the class is form-floating
           For regular label use form group and label first then input 
           -->
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="name" value="{{ old("name") }}"/>
             <label class="form-label" for="name">{{__("code_gen_type.name")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="tel" class="form-control" name="phone" value="{{ old("phone") }}"/>
             <label class="form-label" for="phone">{{__("code_gen_type.phone")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <textarea rows="4" cols="40" class="form-control" name="description">{{ old("description") }}</textarea>
             <label class="form-label" for="description">{{__("code_gen_type.description")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="year_of_birth" value="{{ old("year_of_birth") }}"/>
             <label class="form-label" for="year_of_birth" >{{__("code_gen_type.year_of_birth")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="weight" value="{{ old("weight") }}"/>
             <label class="form-label" for="weight">{{__("code_gen_type.weight")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="date" class="form-control" name="birthday" value="{{ old("birthday") }}"/>
             <label class="form-label" for="birthday">{{__("code_gen_type.birthday")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="time" class="form-control" name="tea_time" value="{{ old("tea_time") }}"/>             
             <label class="form-label" for="tea_time">{{__("code_gen_type.tea_time")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="datetime-local" class="form-control" name="takeoff" value="{{ old("takeoff") }}"/>
             <label class="form-label" for="takeoff">{{__("code_gen_type.takeoff")}}</label>
           </div>
                      
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="price" value="{{ old("price") }}"/>
             <label class="form-label" for="price">{{__("code_gen_type.price")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="big_price" value="{{ old("big_price") }}"/>
             <label class="form-label" for="big_price">{{__("code_gen_type.big_price")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <fieldset class="form-group d-sm-flex flex-wrap mt-5 mb-3 ms-2">
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.redactor") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="0" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.student") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="1" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.pilot") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="2" class="form-check-input   me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.instructor") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="3" class="form-check-input   me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.winch_man") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="4" class="form-check-input   me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.tow_pilot") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="5" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.president") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="6" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.accounter") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="7" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.secretary") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="8" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">{{__("code_gen_type.qualifications.mechanic") }}</label>
               <input type="checkbox" name="qualifications_boxes[]" value="9" class="form-check-input me-3" />
             </div>
           </fieldset>

             <label class="form-label" for="qualifications">{{__("code_gen_type.qualifications")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <select class="form-select" name="color_name" id="color_name">
			    <option value="blue">{{__("code_gen_type.color_name.blue") }}</option>
			    <option value="red">{{__("code_gen_type.color_name.red") }}</option>
			    <option value="green">{{__("code_gen_type.color_name.green") }}</option>
			    <option value="white">{{__("code_gen_type.color_name.white") }}</option>
			    <option value="black">{{__("code_gen_type.color_name.black") }}</option>
			</select>
             <label class="form-label" for="color_name">{{__("code_gen_type.color_name")}}</label>

           </div>
           
           <div class="form-floating mb-2 border">
             <input type="file" class="form-control" name="picture" value="{{ old("picture") }}"/>
             <label class="form-label" for="picture">{{__("code_gen_type.picture")}}</label>
           </div>
           
           <div class="form-floating mb-2 border">
             <input type="file" class="form-control" name="attachment" value="{{ old("attachment") }}"/>
             <label class="form-label" for="attachment">{{__("code_gen_type.attachment")}}</label>
           </div>
           
           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection