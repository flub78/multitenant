<!-- Attachment create.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
@endphp

@extends('layouts.app')

@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="card uper">
  <div class="card-header">
    {{__('attachment.new')}}
  </div>
  <div class="card-body">
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div><br />
    @endif

    <form method="post" action="{{ route('attachment.store') }}" enctype="multipart/form-data">
      @csrf

      <input type="hidden" name="referenced_table" value="{{ $referenced_table }}">
      <input type="hidden" name="referenced_id" value="{{ $referenced_id }}">

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="user_id" value="{{ old("user_id") }}" />
        <label class="form-label" for="user_id">{{__("attachment.user_id")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="filename" value="{{ old("filename") }}" />
        <label class="form-label" for="filename">{{__("attachment.filename")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="description" value="{{ old("description") }}" />
        <label class="form-label" for="description">{{__("attachment.description")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="file" class="form-control" name="file" value="{{ old("file") }}" />
        <label class="form-label" for="file">{{__("attachment.file")}}</label>
      </div>


      @button_submit({{__('general.submit')}})

    </form>
  </div>
</div>
@endsection