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
          <td>{{__('configuration.key')}}</td>
          <td>{{__('configuration.value')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($configurations as $configuration)
        <tr>
        
            <td>{{$configuration->key}}</td>
            <td>{{$configuration->value}}</td>
            
            <td><a href="{{ route('configuration.edit', $configuration->key)}}" class="btn btn-primary">{{__('general.edit')}}</a></td>
            
            <td>
                <form action="{{ route('configuration.destroy', $configuration->key)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('configuration')}}/create"><button type="submit" class="btn btn-primary" >@lang('configuration.add')</button></a> 
</div>  
@endsection


