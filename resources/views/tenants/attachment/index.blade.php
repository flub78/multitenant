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
    @button_create({{url('attachment')}}, {{__('attachment.add')}})
    </div>  
  
  <div class="container-fluid mb-3">
  <table class="table table-striped"  id="maintable">
    <caption>{{__('attachment.title')}}</caption>
    <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>

          <th> {{__('attachment.referenced_table')}} </th>
          <th> {{__('attachment.referenced_id')}} </th>
          <th> {{__('attachment.user_id')}} </th>
          <th> {{__('attachment.description')}} </th>
          <th> {{__('attachment.file')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($attachments as $attachment)
        <tr>
          <td> <a href="{{ route('attachment.edit', $attachment->id) }}" class="btn btn-primary" dusk="edit_{{ $attachment->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("attachment.destroy", $attachment->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $attachment->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
 </td>
          <td> {{$attachment->referenced_table}}</td>
          <td> {{$attachment->referenced_id}}</td>
          <td> {{$attachment->user_id}}</td>
          <td> {{$attachment->description}}</td>
          <td> {!! Blade::download("attachment.file", $attachment->id, "file", $attachment->file, "attachment.file") !!}</td>
		              
        </tr>
        @endforeach
    </tbody>
  </table>
  </div> 
</div> <!-- content div --> 
@endsection


