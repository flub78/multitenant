<!-- Tenant edit.blade.php -->

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    Edit tenant
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
    
      <form method="post" action="{{ route('tenants.update', $tenant->id ) }}">
          @csrf
          @method('PATCH')
              
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="id" value="{{ old('id', $tenant->id) }}"/>
              <label class="form-label" for="id">Name</label>
          </div>
          
          
          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="domain" value="{{ old('domain', $tenant->domain) }}"/>
              <label class="form-label" for="domain">Domain</label>
          </div>

          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="email" value="{{ old('email', $tenant->email) }}"/>
              <label class="form-label" for="email">Email</label>
          </div>

          <div class="form-floating mb-2 border">
              <input type="text" class="form-control" name="db_name" value="{{ old('db_name', $tenant->db_name) }}"/>
              <label class="form-label" for="db_name">Database</label>
          </div>
          
          <button type="submit" class="btn btn-primary">Edit Tenant</button>
      </form>
  </div>
</div>
@endsection