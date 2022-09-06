<!-- index.blade.php -->

@extends('layouts.app')

@section('content')

<div class="uper">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  <table class="table table-striped"  id="maintable">
    <caption>{{__('configuration.title')}}</caption>
    <thead>
        <tr>
          <td> </td>
          <td></td>
          <td> {{__('configuration.key')}} </td>
          <td> {{__('configuration.value')}} </td>
		  
        </tr>
    </thead>
    
    <tbody>
        @foreach($configurations as $configuration)
        <tr>
          <td> <a href="{{ route('configuration.edit', $configuration->key) }}" class="btn btn-primary" dusk="edit_{{ $configuration->key }}">
              <i class="fa-solid fa-pen-to-square"></i></a>  </td>
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
  
    @button_create({{url('configuration')}}, {{__('configuration.add')}}) 
</div>  
@endsection


