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
    <caption>{{__('code_gen_type.title')}}</caption>
    <thead>
        <tr>
          <th></th>
          <th> {{__('code_gen_type.name')}} </th>
          <th> {{__('code_gen_type.phone')}} </th>
          <th> {{__('code_gen_type.description')}} </th>
          <th> {{__('code_gen_type.year_of_birth')}} </th>
          <th> {{__('code_gen_type.weight')}} </th>
          <th> {{__('code_gen_type.birthday')}} </th>
          <th> {{__('code_gen_type.tea_time')}} </th>
          <th> {{__('code_gen_type.takeoff')}} </th>
          <th> {{__('code_gen_type.price')}} </th>
          <th> {{__('code_gen_type.big_price')}} </th>
          <th> {{__('code_gen_type.qualifications')}} </th>
          <th> {{__('code_gen_type.color_name')}} </th>
          <th> {{__('code_gen_type.picture')}} </th>
          <th> {{__('code_gen_type.attachment')}} </th>
          <th> {{__('general.edit')}}   </th>
          <th> {{__('general.delete')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($code_gen_types as $code_gen_type)
        <tr>
          <td></td>
          <td> {{$code_gen_type->name}}</td>
          <td> {{$code_gen_type->phone}}</td>
          <td> {{$code_gen_type->description}}</td>
          <td> {{$code_gen_type->year_of_birth}}</td>
          <td> <div align="right">{!! Blade::float($code_gen_type->weight) !!}</div></td>
          <td> {{$code_gen_type->birthday}}</td>
          <td> {{$code_gen_type->tea_time}}</td>
          <td> {{$code_gen_type->takeoff}}</td>
          <td> <div align="right">{!! Blade::currency($code_gen_type->price) !!}</div></td>
          <td> <div align="right">{!! Blade::currency($code_gen_type->big_price) !!}</div></td>
          <td> {!! Blade::bitfield("code_gen_types", "qualifications", $code_gen_type->qualifications) !!}</td>
          <td> {!! Blade::enumerate("code_gen_type.color_name", $code_gen_type->color_name) !!}</td>
          <td> {!! Blade::picture("code_gen_type.picture", $code_gen_type->id, "picture", $code_gen_type->picture) !!}</td>
          <td> {!! Blade::download("code_gen_type.file", $code_gen_type->id, "attachment", $code_gen_type->attachment, "Attachment") !!}</td>
		              
          <td> <a href="{{ route('code_gen_type.edit', $code_gen_type->id) }}" class="btn btn-primary" dusk="edit_{{ $code_gen_type->id }}">{{ __('general.edit') }}</a>  </td>
          <td> <form action="{{ route("code_gen_type.destroy", $code_gen_type->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $code_gen_type->id }}">{{__('general.delete')}}</button>
                 </form>
 </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
          <th></th>
          <th> {{__('code_gen_type.name')}} </th>
          <th> {{__('code_gen_type.phone')}} </th>
          <th> {{__('code_gen_type.description')}} </th>
          <th> {{__('code_gen_type.year_of_birth')}} </th>
          <th> {{__('code_gen_type.weight')}} </th>
          <th> {{__('code_gen_type.birthday')}} </th>
          <th> {{__('code_gen_type.tea_time')}} </th>
          <th> {{__('code_gen_type.takeoff')}} </th>
          <th> {{__('code_gen_type.price')}} </th>
          <th> {{__('code_gen_type.big_price')}} </th>
          <th> {{__('code_gen_type.qualifications')}} </th>
          <th> {{__('code_gen_type.color_name')}} </th>
          <th> {{__('code_gen_type.picture')}} </th>
          <th> {{__('code_gen_type.attachment')}} </th>
		  
          <th> {{__('general.edit')}}   </th>
          <th> {{__('general.delete')}} </th>
        </tr>

    </tfoot>
  </table>
    @button_create({{url('code_gen_type')}}, {{__('code_gen_type.add')}}) 
</div>  
@endsection


