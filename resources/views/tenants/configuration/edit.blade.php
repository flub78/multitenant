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
    Edit configuration
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
    
      <form method="post" action="{{ route('configuration.update', $configuration->key ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              
              <label for="key">Key</label>
              <input type="text" class="form-control" name="key" value="{{ old('id', $configuration->key) }}"/>
          </div>
          
          <div class="form-group">
              <label for="value">Value</label>
              <input type="text" class="form-control" name="value" value="{{ old('value', $configuration->value) }}"/>
          </div>

          <button type="submit" class="btn btn-primary">Edit Configuration</button>
      </form>
  </div>
</div>
@endsection