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
    <caption>Tenant Configuration</caption>
    <thead>
        <tr>
          <td>Key</td>
          <td>Value</td>
          <td >Edit</td>
          <td >Delete</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($configurations as $configuration)
        <tr>
        
            <td>{{$configuration->key}}</td>
            <td>{{$configuration->value}}</td>
            
            <td><a href="{{ route('configuration.edit', $configuration->key)}}" class="btn btn-primary">Edit</a></td>
            
            <td>
                <form action="{{ route('configuration.destroy', $configuration->key)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('configuration')}}/create"><button type="submit" class="btn btn-primary" >@lang('general.create') @lang('configurations.element')</button></a> 
</div>  
@endsection


