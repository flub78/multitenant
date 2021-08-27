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
    <caption>{{__('role.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('role.name')}}</td>
          <td>{{__('role.description')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($roles as $role)
        <tr>
        
            <td>{{$role->name}}</td>
            <td>{{$role->description}}</td>
            
            <td><a href="{{ route('role.edit', $role->id)}}" class="btn btn-primary">{{__('general.edit')}}</a></td>
            
            <td>
                <form action="{{ route('role.destroy', $role->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('role')}}/create"><button type="submit" class="btn btn-primary" >@lang('role.add')</button></a> 
</div>  
@endsection


