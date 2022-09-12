<!-- index.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
use App\Helpers\DateFormat;
@endphp

@extends('layouts.app')

@section('content')

<div class="uper  d-flex flex-column">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  
  @if(session()->get('error'))
    <div class="alert alert-danger">
      {{ session()->get('error') }}  
    </div><br />
  @endif
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('role.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>
		  
          <th> {{__('role.name')}} </th>
          <th> {{__('role.description')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($roles as $role)
        <tr>
          <td> <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary" dusk="edit_{{ $role->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("role.destroy", $role->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $role->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
          </td>
          <td> {{$role->name}}</td>
          <td> {{$role->description}}</td>		              
          
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
  
    @button_create({{url('role')}}, {{__('role.add')}}) 
</div> <!-- content div --> 
@endsection


