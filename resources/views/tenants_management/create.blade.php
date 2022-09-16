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
          @csrf
          
          <div class="form-floating mb-2 border">              
              <input type="text" class="form-control" name="id" value="{{ old('id') }}"/>
              <label class="form-label" for="id">Tenant id</label>
          </div>
@if (true)          
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="email" value="{{ old('email') }}"/>
              <label class="form-label" for="email">Email</label>
          </div>
          
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="db_name" value="{{ old('db_name') }}"/>
              <label class="form-label" for="db_name">Database</label>
          </div>
@endif          
           <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="domain" value="{{ old('domain') }}"/>
              <label class="form-label" for="domain">Domain</label>
          </div>
          
          
          <button type="submit" class="btn btn-primary">Add tenant</button>
      </form>
  </div>
</div>
@endsection