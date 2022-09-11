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
    <caption>{{__('metadata.title')}}</caption>
    <thead>
        <tr>
          <td ></td>
          <td ></td>
          <td>{{__('metadata.table')}}</td>
          <td>{{__('metadata.field')}}</td>
          <td>{{__('metadata.subtype')}}</td>
          <td>{{__('metadata.options')}}</td>
          <td>{{__('metadata.foreign_key')}}</td>
          <td>{{__('metadata.target_table')}}</td>
          <td>{{__('metadata.target_field')}}</td>
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
  
    @button_create({{url('metadata')}}, {{__('metadata.add')}}) 
</div> <!-- content div --> 
@endsection


