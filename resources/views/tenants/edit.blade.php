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
          <div class="form-group">
              @csrf
              @method('PATCH')
              
              <label for="id">Name</label>
              <input type="text" class="form-control" name="id" value="{{ old('id', $tenant->id) }}"/>
          </div>
          
          <div class="form-group">
              <label for="domain">Domain</label>
              <input type="text" class="form-control" name="domain" value="{{ old('domain', $tenant->domain) }}"/>
          </div>
          
          <button type="submit" class="btn btn-primary">Edit Tenant</button>
      </form>
  </div>
</div>
@endsection