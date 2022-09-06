<!-- index.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
@endphp

@extends('layouts.app')

@section('content')


<div class="uper">
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
  <table class="table table-striped"  id="maintable">
    <caption>{{__('user_role.title')}}</caption>
    <thead>
        <tr>
          <td ></td>
          <td ></td>
          <td>{{__('user_role.user_id')}}</td>
          <td>{{__('user_role.role_id')}}</td>
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
  
    <a href="{{url('user_role')}}/create"><button type="submit" class="btn btn-primary" >@lang('user_role.add')</button></a> 
</div>  
@endsection


