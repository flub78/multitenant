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
        <input type="text" class="form-control" name="table" id="table" value="{{ old("table") }}" />
        <label class="form-label" for="table">{{__("metadata.table")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <input type="text" class="form-control" name="field" id="field" value="{{ old("field") }}" />
        <label class="form-label" for="field">{{__("metadata.field")}}</label>
      </div>

      <div class="form-floating mb-2 border">
        <select class="form-select big-select" name="subtype" id="subtype">
          <option value=""></option>
          <option value="email">email</option>
          <option value="checkbox">checkbox</option>
          <option value="enumerate">enumerate</option>
          <option value="bitfield">bitfield</option>
          <option value="picture">picture</option>
          <option value="file">file</option>
          <option value="currency">currency</option>
          <option value="foreign_key">foreign_key</option>
          <option value="bitfield_boxes">bitfield_boxes</option>
          <option value="password_with_confirmation">password_with_confirmation</option>
          <option value="password_confirmation">password_confirmation</option>
          <option value="phone">phone</option>
          <option value="color">color</option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
        </select>

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
  /** Callback for the table select */
  function tableSelectChanged() {
    var selectedTable = document.getElementById('table_select').value;
    replaceFieldBySelect(selectedTable);
  }

  /**
   * Replace the table input by a select with the available tables
   */
  function replaceTableBySelect() {
    const tables = @json($tables);
    const tableArray = Object.values(tables);
    // console.log('Available tables:', tables);

    let selectHtml = '<select class="form-select" id="table_select" name="table" onchange="tableSelectChanged()">';

    for (const table of tableArray) {
      selectHtml += `<option value="${table}">${table}</option>`;
    }

    selectHtml += '</select>';

    document.getElementById('table').outerHTML = selectHtml;
  }

  /**
   * Replace the field input by a select with the available tables
   */
  function replaceFieldBySelect(table) {
    const columns = @json($columns);

    const fields = columns[table];

    let selectHtml = '<select class="form-select" id="field" name="field">';
    for (const field of fields) {
      selectHtml += `<option value="${field}">${field}</option>`;
    }
    selectHtml += '</select>';

    document.getElementById('field').outerHTML = selectHtml;
  }

  /**
   * When the page is displayed
   */
  document.addEventListener('DOMContentLoaded', function() {
    const tables = @json($tables);
    const tableArray = Object.values(tables);

    replaceTableBySelect();
    replaceFieldBySelect(tableArray[0]);
  });
</script>

@endsection