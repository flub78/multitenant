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
    @button_create({{url('user_role')}}, {{__('user_role.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('user_role.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>

          <th> {{__('user_role.user_id')}} </th>
          <th> {{__('user_role.role_id')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($user_roles as $user_role)
        <tr>
            
            <td><a href="{{ route('user_role.edit', $user_role->id)}}" class="btn btn-primary">
              <i class="fa-solid fa-pen-to-square"></i></a></td>
            
            <td>
                <form action="{{ route('user_role.destroy', $user_role->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">
                    <i class="fa-solid fa-trash"></i></button>
                </form>
            </td>
        
            <td>{{$user_role->user_name}}</td>
            <td>{{$user_role->role_name}}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
  
</div> <!-- content div --> 
@endsection


