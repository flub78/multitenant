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
    <caption>{{__('metadata.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('metadata.table')}}</td>
          <td>{{__('metadata.field')}}</td>
          <td>{{__('metadata.subtype')}}</td>
          <td>{{__('metadata.options')}}</td>
          <td>{{__('metadata.foreign_key')}}</td>
          <td>{{__('metadata.target_table')}}</td>
          <td>{{__('metadata.target_field')}}</td>
          <td >{{__('general.edit')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($metadata as $meta)
        <tr>
            <td>{{$meta->table}}</td>
            <td>{{$meta->field}}</td>
            <td>{{$meta->subtype}}</td>
            <td>{{$meta->options}}</td>
            <td>
            	<input type="checkbox"   {{($meta->foreign_key) ? 'checked' : ''}} onclick="return false;" />
            </td>
            <td>{{$meta->target_table}}</td>
            <td>{{$meta->target_field}}</td>
            
            <td><a href="{{ route('metadata.edit', $meta->id)}}" class="btn btn-primary" dusk="edit_{{$meta->table}}_{{$meta->field}}">{{__('general.edit')}}</a></td>
            
            <td>
                <form action="{{ route('metadata.destroy', $meta->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" dusk="delete_{{$meta->table}}_{{$meta->field}}" >{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('metadata')}}/create"><button type="submit" class="btn btn-primary" >@lang('metadata.add')</button></a> 
</div>  
@endsection


