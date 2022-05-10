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
    <caption>{{__('profile.title')}}</caption>
    <thead>
        <tr>
          <td> {{__('profile.first_name')}} </td>
          <td> {{__('profile.last_name')}} </td>
          <td> {{__('profile.birthday')}} </td>
          <td> {{__('profile.user_id')}} </td>
          <td> {{__('general.edit')}}   </td>
          <td> {{__('general.delete')}} </td>
        </tr>
    </thead>
    
    <tbody>
        @foreach($profiles as $profile)
        <tr>
          <td> {{$profile->first_name}}</td>
          <td> {{$profile->last_name}}</td>
          <td> {{$profile->birthday}}</td>
          <td> {{$profile->user_image()}}</td>
		              
          <td> <a href="{{ route('profile.edit', $profile->id) }}" class="btn btn-primary" dusk="edit_{{ $profile->id }}">{{ __('general.edit') }}</a>  </td>
          <td> <form action="{{ route("profile.destroy", $profile->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $profile->id }}">{{__('general.delete')}}</button>
                 </form>
 </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
    @button_create({{url('profile')}}, {{__('profile.add')}}) 
</div>  
@endsection


