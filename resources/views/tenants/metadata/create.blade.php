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
           
           <div class="form-floating m-5 border border-danger">
             <input type="checkbox" class="form-check-input m-3" name="options" id="options" value="1"  {{old("options") ? 'checked' : ''}}/>
             <label class="form-label m-3" for="options">{{__("metadata.options")}}</label>
          </div>
          
           <div class="form-floating mb-2 border border-primary">
             <input type="text" class="form-control" name="foreign_key" value="{{ old("foreign_key") }}"/>
             <label class="form-label" for="foreign_key">{{__("metadata.foreign_key")}}</label>
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