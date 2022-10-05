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
    @button_create({{url('motd')}}, {{__('motd.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('motd.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>

          <th> {{__('motd.title')}} </th>
          <th> {{__('motd.message')}} </th>
          <th> {{__('motd.publication_date')}} </th>
          <th> {{__('motd.end_date')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($motds as $motd)
        <tr>
          <td> <a href="{{ route('motd.edit', $motd->id) }}" class="btn btn-primary" dusk="edit_{{ $motd->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("motd.destroy", $motd->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $motd->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
 </td>
          <td> {{$motd->title}}</td>
          <td> {{$motd->message}}</td>
          <td> {{DateFormat::to_local_date($motd->publication_date)}}</td>
          <td> {{DateFormat::to_local_date($motd->end_date)}}</td>
		              
        </tr>
        @endforeach
    </tbody>
  </table>
  </div> 
</div> <!-- content div --> 
@endsection


