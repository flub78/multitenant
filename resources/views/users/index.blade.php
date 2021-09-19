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
  <table class="table table-striped"  id="maintable">
    <caption>{{__('users.title')}}</caption>
    <thead>
        <tr>
          <td> {{__('users.name')}}     </td>
          <td> {{__('users.email')}}    </td>
          <td> {{__('users.admin')}}    </td>
          <td> {{__('users.active')}}   </td>
          <td> {{__('general.edit')}}   </td>
          <td> {{__('general.delete')}} </td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($users as $user)
        <tr>
            <td> {{$user->name}} </td>
            <td> {!! Blade::email($user->email) !!} </td>
            <td> {!! Blade::checkbox($user->admin) !!} </td>
            <td> {!! Blade::checkbox($user->active) !!} </td>
            
            <td> {!! Blade::button_edit( route('users.edit', $user->id), "edit_$user->name", __('general.edit')) !!} </td>
            <td> {!! Blade::button_delete( $route=route('users.destroy', $user->id), $dusk="delete_$user->name", $label=__('general.delete')) !!} </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    @button_create({{url('users')}}, {{__('users.add')}}) 
</div>  
@endsection


