<!-- index.blade.php -->

@extends('layouts.app')

@section('content')


<div class="uper">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  <table class="table table-striped"  id="maintable">
    <caption>Tenants</caption>
    <thead>
        <tr>
          <td>Name</td>
          <td>Domain</td>
          <td>Email</td>
          <td>Database</td>
          <td >Edit</td>
          <td >Delete</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($tenants as $tenant)
        <tr>
            <td>{{$tenant->id}}</td>            
			<td><a href="{{ $tenant->url()}}" >{{$tenant->domain}}</a></td>
			<td>{{$tenant->email}}</td>
			<td>{{$tenant->db_name}}</td>

            <td><a href="{{ route('tenants.edit', $tenant->id)}}" class="btn btn-primary" dusk="edit_{{$tenant->id}}">Edit</a></td>
            
            <td>
                <form action="{{ route('tenants.destroy', $tenant->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" dusk="delete_{{$tenant->id}}" >Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('tenants')}}/create"><button type="submit" class="btn btn-primary" >@lang('general.create') tenant</button></a> 
</div>  
@endsection


