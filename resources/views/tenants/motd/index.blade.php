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
    <caption>{{__('motd.title')}}</caption>
    <thead>
        <tr>
          <td> {{__('motd.title')}} </td>
          <td> {{__('motd.message')}} </td>
          <td> {{__('motd.publication_date')}} </td>
          <td> {{__('motd.end_date')}} </td>
          <td> {{__('general.edit')}}   </td>
          <td> {{__('general.delete')}} </td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($motds as $motd)
        <tr>
          <td> {{$motd->title}}</td>
          <td> {{$motd->message}}</td>
          <td> {{$motd->publication_date}}</td>
          <td> {{$motd->end_date}}</td>
		              
          <td> <a href="{{ route('motd.edit', $motd->id) }}" class="btn btn-primary" dusk="edit_{{ $motd->id }}">{{ __('general.edit') }}</a>  </td>
          <td> <form action="{{ route("motd.destroy", $motd->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $motd->id }}">{{__('general.delete')}}</button>
                 </form>
 </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    @button_create({{url('motd')}}, {{__('motd.add')}}) 
</div>  
@endsection


