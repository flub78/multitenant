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
    @button_create({{url('personal_access_token')}}, {{__('personal_access_token.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('personal_access_token.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>

          <th> {{__('personal_access_token.tokenable_type')}} </th>
          <th> {{__('personal_access_token.tokenable_id')}} </th>
          <th> {{__('personal_access_token.name')}} </th>
          <th> {{__('personal_access_token.token')}} </th>
          <th> {{__('personal_access_token.abilities')}} </th>
          <th> {{__('personal_access_token.last_used_at')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($personal_access_tokens as $personal_access_token)
        <tr>
          <td> <a href="{{ route('personal_access_token.edit', $personal_access_token->id) }}" class="btn btn-primary" dusk="edit_{{ $personal_access_token->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("personal_access_token.destroy", $personal_access_token->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $personal_access_token->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
 </td>
          <td> {{$personal_access_token->tokenable_type}}</td>
          <td> {{$personal_access_token->tokenable_id}}</td>
          <td> {{$personal_access_token->name}}</td>
          <td> {{$personal_access_token->token}}</td>
          <td> {{$personal_access_token->abilities}}</td>
          <td> {{$personal_access_token->last_used_at}}</td>
		              
        </tr>
        @endforeach
    </tbody>
  </table>
  </div> 
</div> <!-- content div --> 
@endsection


