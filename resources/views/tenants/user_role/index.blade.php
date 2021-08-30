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
    <caption>{{__('user_roles.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('user_roles.user_id')}}</td>
          <td>{{__('user_roles.role_id')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($user_roles as $user_role)
        <tr>
        
            <td>{{$user_role->name}}</td>
            <td>{{$user_role->description}}</td>
            
            <td><a href="{{ route('user_role.edit', $user_role->id)}}" class="btn btn-primary">{{__('general.edit')}}</a></td>
            
            <td>
                <form action="{{ route('user_role.destroy', $role->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('user_role')}}/create"><button type="submit" class="btn btn-primary" >@lang('user_roles.add')</button></a> 
</div>  
@endsection


