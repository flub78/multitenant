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
    <caption>{{__('motd.current_messages')}}</caption>
    <thead>
        <tr>
          <td> {{__('motd.title')}} </td>
          <td> {{__('motd.message')}} </td>
          <td> {{__('motd.publication_date')}} </td>
          <td> {{__('motd.end_date')}} </td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($motds as $motd)
        <tr>
          <td> {{$motd->title}}</td>
          <td> {{$motd->message}}</td>
          <td> {{$motd->publication_date}}</td>
          <td> {{$motd->end_date}}</td>		              
        </tr>
        @endforeach
    </tbody>
  </table>
  
</div>  
@endsection


