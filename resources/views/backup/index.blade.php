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
    <caption>Local backups</caption>
    <thead>
        <tr>
          <td>Number</td>
          <td>Backup</td>
          <td >Restore</td>
          <td >Delete</td>
        </tr>
    </thead>

    <tbody>
        @foreach($backups as $backup)
        <tr>
            <td>{{$backup['id']}}</td>
            <td>{{$backup['filename']}}</td>
            <td><a href="{{ route('backup.restore', $backup['id'])}}" class="btn btn-primary">Restore</a></td>
            <td>
                <form action="{{ route('backup.destroy', $backup['id'])}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    <a href="{{url('backup')}}/create"><button type="submit" class="btn btn-primary" >@lang('general.create') backup</button></a> 
</div>  
@endsection


