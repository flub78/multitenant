<!-- index.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
use App\Helpers\DateFormat;
@endphp

@extends('layouts.app')

@section('content')

<div class="uper d-flex flex-column">

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

  <div class="mb-3">
    @button_create({{url('user')}}, {{__('user.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('user.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>
		  
          <th> {{__('user.name')}} </th>
          <th> {{__('user.email')}} </th>
          <th> {{__('user.admin')}} </th>
          <th> {{__('user.active')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($users as $user)
        <tr>
          <td> <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary" dusk="edit_{{ $user->name }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("user.destroy", $user->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $user->name }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
          </td>
          <td> {{$user->name}}</td>
          <td> <A HREF="mailto:{{$user->email}}">{{$user->email}}</A></td>
          <td> <input type="checkbox" {{ ($user->admin) ? "checked" : "" }}  onclick="return false;" /></td>
          <td> <input type="checkbox" {{ ($user->active) ? "checked" : "" }}  onclick="return false;" /></td>
		              

        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
  
</div> <!-- content div --> 
@endsection


