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
    {{__('users.change_password') . " " . $user->name}}
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
    
      <form method="post" action="{{ route('users.password', $user->id ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')              
          </div>
           
          <div class="form-group">
              <label for="cases">{{__('users.previous_password')}}</label>
              <input type="password" class="form-control" name="password" value="{{ old('password') }}"/>
          </div>
          
          <div class="form-group">
              <label for="cases">{{__('users.new_password')}}</label>
              <input type="password" class="form-control" name="new_password" value="{{ old('new_password') }}"/>
          </div>

          <div class="form-group">
              <label for="cases">{{__('users.confirm_new')}}</label>
              <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}"/>
          </div>

          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection