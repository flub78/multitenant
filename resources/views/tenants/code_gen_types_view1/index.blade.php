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
    <caption>{{__('code_gen_types_view1.title')}}</caption>
    <thead>
        <tr>
          <td> {{__('code_gen_types_view1.name')}} </td>
          <td> {{__('code_gen_types_view1.description')}} </td>
          <td> {{__('code_gen_types_view1.tea_time')}} </td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($code_gen_types_view1 as $code_gen_types_view1)
        <tr>
          <td> {{$code_gen_types_view1->name}}</td>
          <td> {{$code_gen_types_view1->description}}</td>
          <td> {{$code_gen_types_view1->tea_time}}</td>
		              
        </tr>
        @endforeach
    </tbody>
  </table>
  
</div>  
@endsection


