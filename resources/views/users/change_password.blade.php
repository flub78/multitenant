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
    {{__('user.change_password') . " " . $user->name}}
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
    
      <form method="post" action="{{ route('change_password.password', $user->id ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')              
          </div>
           
          <div class="form-group">
              <label for="cases">{{__('user.email')}}</label>
              <input type="text" class="form-control" name="email" value="{{ old('email', $user->email) }}"/>
          </div>

          <div class="form-group">
              <label for="cases">{{__('user.current_password')}}</label>
              <input type="password" class="form-control" name="password" value="{{ old('password') }}"/>
          </div>
          
          <div class="form-group">
              <label for="cases">{{__('user.new_password')}}</label>
              <input type="password" class="form-control" name="new_password" value="{{ old('new_password') }}"/>
          </div>

          <div class="form-group">
              <label for="cases">{{__('user.confirm_new')}}</label>
              <input type="password" class="form-control" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}"/>
          </div>

          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection