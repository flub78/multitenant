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
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('configuration.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>
		  
          <th> {{__('configuration.key')}} </th>
          <th> {{__('configuration.value')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($configurations as $configuration)
        <tr>
          <td> <a href="{{ route('configuration.edit', $configuration->key) }}" class="btn btn-primary" dusk="edit_{{ $configuration->key }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("configuration.destroy", $configuration->key)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $configuration->key }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
          </td>
          <td> {{$configuration->key}}</td>
          <td> {{$configuration->value}}</td>
		              
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
  
    @button_create({{url('configuration')}}, {{__('configuration.add')}}) 
</div> <!-- content div --> 
@endsection


