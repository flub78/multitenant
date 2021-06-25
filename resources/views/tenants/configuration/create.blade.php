<!-- Users create.blade.php -->

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    New configuration
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
    
      <form method="post" action="{{ route('configuration.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="key">Key</label>
              <input type="text" class="form-control" name="key" value="{{ old('key') }}"/>
          </div>
          
          <div class="form-group">
              <label for="value">Value</label>
              <input type="text" class="form-control" name="value" value="{{ old('value') }}"/>
          </div>
          
          <button type="submit" class="btn btn-primary">Add a new configuration entry</button>
      </form>
  </div>
</div>
@endsection