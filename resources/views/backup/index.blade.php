<!-- index.blade.php -->

@extends('layouts.app')

@section('content')


<div class="uper">

  @if(session()->has('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  
  @if(session()->has('error'))
    <div class="alert alert-danger">
      {{ session()->get('error') }}  
    </div><br />
  @endif
  
  
  <table class="table table-striped"  id="maintable">
    <caption>{{__('backup.title')}}</caption>
    <thead>
        <tr>
          <td>{{__('backup.number')}}</td>
          <td>{{__('backup.backup')}}</td>
          <td >{{__('backup.restore')}}</td>
          <td >{{__('general.delete')}}</td>
        </tr>
    </thead>

    <tbody>
        @foreach($backups as $backup)
        <tr>
            <td>{{$backup['id']}}</td>
            <td> <a href="{{ route('backup.download', $backup['id'])}}" > {{$backup['filename']}} </a></td>
            <td><a href="{{ route('backup.restore', $backup['id'])}}" class="btn btn-primary" dusk="restore_{{$backup['id']}}">{{__('backup.restore')}}</a></td>
            <td>
                <form action="{{ route('backup.destroy', $backup['id'])}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" dusk="delete_{{$backup['id']}}">{{__('general.delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('backup')}}/create"><button type="submit" class="btn btn-primary" dusk="new_backup">{{__('backup.new')}}</button></a> 
    
    <section>
    
    <form action="{{route('backup.upload')}}" method="post" enctype="multipart/form-data">
    
    <h5 class="text mb-5"></h5>
    <h5 class="text mb-5">{{__('backup.upload_backup')}}</h5>
       @csrf

	   <input type="file" name="backup_file" />

       <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
            {{__('general.upload')}}
       </button>
    </form>
    </section>
            
</div>  
@endsection


