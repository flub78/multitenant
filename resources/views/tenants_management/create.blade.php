<!-- Tenant create.blade.php -->

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    New tenant
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
    
      <form method="post" action="{{ route('tenants.store') }}">
          <div class="form-group">
              @csrf
              
              <label for="id">Tenant id</label>
              <input type="text" class="form-control" name="id" value="{{ old('id') }}"/>
          </div>
@if (true)          
          <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" value="{{ old('email') }}"/>
          </div>
          
          <div class="form-group">
              <label for="db_name">Database</label>
              <input type="text" class="form-control" name="db_name" value="{{ old('db_name') }}"/>
          </div>
@endif          
           <div class="form-group">
              <label for="domain">Domain</label>
              <input type="text" class="form-control" name="domain" value="{{ old('domain') }}"/>
          </div>
          
          
          <button type="submit" class="btn btn-primary">Add tenant</button>
      </form>
  </div>
</div>
@endsection