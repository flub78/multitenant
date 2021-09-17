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
    <caption>{{__('users.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('users.name')}}</td>
          <td>{{__('users.email')}}</td>
          <td>{{__('users.admin')}}</td>
          <td>{{__('users.active')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>@email({{$user->email}})</td>
            <td>
            	<input type="checkbox"   {{($user->admin) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>
            	<input type="checkbox"   {{($user->active) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td><a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary" dusk="edit_{{$user->name}}">{{__('general.edit')}}</a></td>
            
            <td>
                <form action="{{ route('users.destroy', $user->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" dusk="delete_{{$user->name}}" >{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('users')}}/create"><button type="submit" class="btn btn-primary" >@lang('users.add')</button></a> 
</div>  
@endsection


