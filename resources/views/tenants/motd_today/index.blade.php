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
    <caption>{{__('motd_today.title')}}</caption>
    <thead>
        <tr>

          <th> {{__('motd.title')}} </th>
          <th> {{__('motd.message')}} </th>
          <th> {{__('motd.publication_date')}} </th>
          <th> {{__('motd.end_date')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($motd_todays as $motd_today)
        <tr>
          <td> {{$motd_today->title}}</td>
          <td> {{$motd_today->message}}</td>
          <td> {{DateFormat::to_local_date($motd_today->publication_date)}}</td>
          <td> {{DateFormat::to_local_date($motd_today->end_date)}}</td>
		              
        </tr>
        @endforeach
    </tbody>
  </table>
  </div> 
</div> <!-- content div --> 
@endsection


