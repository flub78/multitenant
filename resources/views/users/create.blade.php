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
    New user
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
    
      <form method="post" action="{{ route('users.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="country_name">Name</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
          </div>
          
          <div class="form-group">
              <label for="cases">Email</label>
              <input type="text" class="form-control" name="email" value="{{ old('email') }}"/>
          </div>
          
           <div class="form-group">
              <label for="cases">Admin</label>
              <input type="checkbox" class="form-control" name="admin" value="1"  {{old('admin') ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="cases">Active</label>
              <input type="checkbox" class="form-control" name="active" value="1"  {{old('active') ? 'checked' : ''}}/>
          </div>
          
          <div class="form-group">
              <label for="cases">Password</label>
              <input type="password" class="form-control" name="password" value="{{ old('password') }}"/>
          </div>

          <div class="form-group">
              <label for="cases">Confirm Password</label>
              <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}"/>
          </div>

          <button type="submit" class="btn btn-primary">Add User</button>
      </form>
  </div>
</div>
@endsection