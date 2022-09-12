<!-- Metadata create.blade.php -->

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
    {{__('metadata.new')}}
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
    
      <form method="post" action="{{ route('metadata.store') }}" enctype="multipart/form-data">
              @csrf
              
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="table" value="{{ old("table") }}"/>
             <label class="form-label" for="table">{{__("metadata.table")}}</label>
          </div>
          
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="field" value="{{ old("field") }}"/>
             <label class="form-label" for="field">{{__("metadata.field")}}</label>
          </div>
          
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="subtype" value="{{ old("subtype") }}"/>
             <label class="form-label" for="subtype">{{__("metadata.subtype")}}</label>
          </div>
           
           <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="options" value="{{ old("options") }}"/>
              <label class="form-label" for="options">{{__("metadata.options")}}</label>
          </div>
          
           <div class="form-group mb-2 border">
              <label class="form-label m-2" for="foreign_key">{{__("metadata.foreign_key")}}</label>
              <input type="checkbox" class="form-check-input m-2" name="foreign_key" id="foreign_key" value="1"  {{old("foreign_key") ? 'checked' : ''}}/>
          </div>
          
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="target_table" value="{{ old("target_table") }}"/>
             <label class="form-label" for="target_table">{{__("metadata.target_table")}}</label>
          </div>
          
           <div class="form-floating mb-2 border">
             <input type="text" class="form-control" name="target_field" value="{{ old("target_field") }}"/>
             <label class="form-label" for="target_field">{{__("metadata.target_field")}}</label>
          </div>

           
           @button_submit({{__('general.submit')}})

      </form>
  </div>
</div>
@endsection