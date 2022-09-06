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
          <td> </td>
          <td></td>
          <td> {{__('role.name')}} </td>
          <td> {{__('role.description')}} </td>
		  
        </tr>
    </thead>
    
    <tbody>
        @foreach($roles as $role)
        <tr>
          <td> <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary" dusk="edit_{{ $role->id }}">
            <i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("role.destroy", $role->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $role->id }}">
                   <i class="fa-solid fa-trash"></i></button>
                 </form>
          </td>
          <td> {{$role->name}}</td>
          <td> {{$role->description}}</td>		              
          
        </tr>
        @endforeach
    </tbody>
  </table>
  
    @button_create({{url('role')}}, {{__('role.add')}}) 
</div>  
@endsection


