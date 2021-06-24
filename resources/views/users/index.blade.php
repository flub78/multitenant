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
    <caption>Users</caption>
    <thead>
        <tr>
          <td>Name</td>
          <td>Email</td>
          <td>Admin</td>
          <td>Active</td>
          <td >Edit</td>
          <td >Delete</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>
            	<input type="checkbox"   {{($user->admin) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>
            	<input type="checkbox"   {{($user->active) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td><a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary">Edit</a></td>
            
            <td>
                <form action="{{ route('users.destroy', $user->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('users')}}/create"><button type="submit" class="btn btn-primary" >@lang('general.create') @lang('users.element')</button></a> 
</div>  
@endsection


