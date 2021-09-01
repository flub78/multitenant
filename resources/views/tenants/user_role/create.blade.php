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
    {{__('user_roles.new')}}
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
    
      <form method="post" action="{{ route('user_role.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="name">{{__('user_roles.user_id')}}</label>
              
              {!! App\Helpers\HtmlHelper::selector($user_list, false, "", 
              ["class" => "form-select", "name" => "user_id", "id" => "user_id"]) !!}

              <label for="description">{{__('user_roles.role_id')}}</label>
              {!! App\Helpers\HtmlHelper::selector($role_list, false, "",
              ["name" => "role_id", "id" => "role_id"]) !!}
          </div>
          
          <button type="submit" class="btn btn-primary">{{__('general.submit')}}</button>
      </form>
  </div>
</div>
@endsection