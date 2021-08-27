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
    {{__('general.edit')}} {{__('role.elt')}}
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
    
      <form method="post" action="{{ route('role.update', $role->id ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              
              <label for="key">{{__('role.name')}}</label>
              <input type="text" class="form-control" name="name" value="{{ old('name', $role->name) }}"/>
          </div>
          
          <div class="form-group">
              <label for="value">{{__('role.description')}}</label>
              <input type="text" class="form-control" name="description" value="{{ old('description', $role->description) }}"/>
          </div>

          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection