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
    {{__('role.new')}}
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
    
      <form method="post" action="{{ route('role.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="name">{{__('role.name')}}</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
          </div>
          
          <div class="form-group">
              <label for="description">{{__('role.description')}}</label>
              <input type="text" class="form-control" name="description" value="{{ old('description') }}"/>
          </div>
          
          <button type="submit" class="btn btn-primary">{{__('general.submit')}}</button>
      </form>
  </div>
</div>
@endsection