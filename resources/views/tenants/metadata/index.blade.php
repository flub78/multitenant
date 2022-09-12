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
    @button_create({{url('metadata')}}, {{__('metadata.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('metadata.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>

          <th> {{__('metadata.table')}} </th>
          <th> {{__('metadata.field')}} </th>
          <th> {{__('metadata.subtype')}} </th>
          <th> {{__('metadata.options')}} </th>
          <th> {{__('metadata.foreign_key')}} </th>
          <th> {{__('metadata.target_table')}} </th>
          <th> {{__('metadata.target_field')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($metadata as $meta)
        <tr>
            <td><a href="{{ route('metadata.edit', $meta->id)}}" class="btn btn-primary" dusk="edit_{{$meta->table}}_{{$meta->field}}">
            	<i class="fa-solid fa-pen-to-square"></i></a></td>
            
            <td>
                <form action="{{ route('metadata.destroy', $meta->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" dusk="delete_{{$meta->table}}_{{$meta->field}}" >
                  	<i class="fa-solid fa-trash"></i></button>
                </form>
            </td>
            
            <td>{{$meta->table}}</td>
            <td>{{$meta->field}}</td>
            <td>{{$meta->subtype}}</td>
            <td>{{$meta->options}}</td>
            <td>
            	<input type="checkbox"   {{($meta->foreign_key) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>{{$meta->target_table}}</td>
            <td>{{$meta->target_field}}</td>
            
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
  
</div> <!-- content div --> 
@endsection


