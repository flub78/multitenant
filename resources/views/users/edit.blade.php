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
    {{__('general.edit')}} {{__('user.elt')}}
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
    
      <form method="post" action="{{ route('user.update', $user->id ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              
              <label for="country_name">{{__('user.name')}}</label>
              <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}"/>
          </div>
          
          <div class="form-group">
              <label for="cases">{{__('user.email')}}</label>
              <input type="text" class="form-control" name="email" value="{{ old('email', $user->email) }}"/>
          </div>

           <div class="form-group">
              <label for="cases">{{__('user.admin')}}</label>
              <input type="checkbox" class="form-control" name="admin" value="1"  {{old('admin', $user->admin) ? 'checked' : ''}}/>
          </div>
          
           <div class="form-group">
              <label for="cases">{{__('user.active')}}</label>
              <input type="checkbox" class="form-control" name="active" value="1"  {{old('active', $user->active) ? 'checked' : ''}}/>
          </div>
          
          <div class="form-group">
              <label for="cases">{{__('user.password')}}</label>
              <input type="password" class="form-control" name="password" value="{{ old('password') }}"/>
          </div>

          <div class="form-group">
              <label for="cases">{{__('user.confirm')}}</label>
              <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}"/>
          </div>

          <button type="submit" class="btn btn-primary">{{__('general.update')}}</button>
      </form>
  </div>
</div>
@endsection