<!-- Users edit.blade.php -->

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    {{__('general.edit')}} {{__('metadata.elt')}}
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
    
      <form method="post" action="{{ route('metadata.update', $metadata->id ) }}">
      
          <div class="form-group">
              @csrf
              @method('PATCH')
              
              <label for="table">{{__('metadata.table')}}</label>
              <input type="text" class="form-control" name="table" value="{{ old('table', $metadata->table) }}"/>
          </div>
          
          <div class="form-group">
              <label for="field">{{__('metadata.field')}}</label>
              <input type="text" class="form-control" name="field" value="{{ old('field', $metadata->field) }}"/>
          </div>

          <div class="form-group">
              <label for="subtype">{{__('metadata.subtype')}}</label>
              <input type="text" class="form-control" name="subtype" value="{{ old('subtype', $metadata->subtype) }}"/>
          </div>
           
          <div class="form-group">
              <label for="options">{{__('metadata.options')}}</label>
              <input type="text" class="form-control" name="options" value="{{ old('options', $metadata->options) }}"/>
          </div>
          
           <div class="form-group">
              <label for="foreign_key">{{__('metadata.foreign_key')}}</label>
              <input type="checkbox" class="form-control" name="foreign_key" value="1"  {{old('foreign_key', $metadata->foreign_key) ? 'checked' : ''}}/>
          </div>
          
          <div class="form-group">              
              <label for="target_table">{{__('metadata.target_table')}}</label>
              <input type="text" class="form-control" name="target_table" value="{{ old('target_table', $metadata->target_table) }}"/>
          </div>
          
          <div class="form-group">
              <label for="target_field">{{__('metadata.target_field')}}</label>
              <input type="text" class="form-control" name="target_field" value="{{ old('target_field', $metadata->target_field) }}"/>
          </div>

          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection