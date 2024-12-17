<!-- Metadata create.blade.php -->

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
    {{__('metadata.new')}}
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

    <form method="post" action="{{ route('metadata.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-floating mb-2 border">
        <select class="form-select big-select" name="table_select" id="table_select" onchange="tableSelectChanged()">
          <option value="calendar_events">calendar_events</option>
          <option value="code_gen_types">code_gen_types</option>
          <option value="code_gen_types_view1">code_gen_types_view1</option>
          <option value="configurations">configurations</option>
          <option value="motd_todays">motd_todays</option>
          <option value="motds">motds</option>
          <option value="personal_access_tokens">personal_access_tokens</option>
          <option value="profiles">profiles</option>
          <option value="roles">roles</option>
          <option value="user_roles">user_roles</option>
          <option value="user_roles_view1">user_roles_view1</option>
          <option value="users">users</option>
        </select>
        <label class="form-label" for="table">{{__("metadata.table")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        {!! $initial_column_select !!}
        <label class="form-label" for="field">{{__("metadata.field")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="subtype" value="{{ old("subtype") }}" />
        <label class="form-label" for="subtype">{{__("metadata.subtype")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="options" value="{{ old("options") }}" />
        <label class="form-label" for="options">{{__("metadata.options")}}</label>
      </div>

      <div class="form-group mb-2 border">
        <label class="form-label m-2" for="foreign_key">{{__("metadata.foreign_key")}}</label>
        <input type="checkbox" class="form-check-input m-2" name="foreign_key" id="foreign_key" value="1" {{old("foreign_key") ? 'checked' : ''}} />
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="target_table" value="{{ old("target_table") }}" />
        <label class="form-label" for="target_table">{{__("metadata.target_table")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="target_field" value="{{ old("target_field") }}" />
        <label class="form-label" for="target_field">{{__("metadata.target_field")}}</label>
      </div>


      @button_submit({{__('general.submit')}})

    </form>
  </div>
</div>

<script>
  function tableSelectChanged() {
    var selectedTable = document.getElementById('table_select').value;
    const column_select = @json($column_select);

    console.log(column_select[selectedTable]);

    document.getElementById('field_select').outerHTML = column_select[selectedTable];
  }
</script>

@endsection