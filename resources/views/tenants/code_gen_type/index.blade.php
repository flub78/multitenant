<!-- index.blade.php -->

@php
use App\Helpers\BladeHelper as Blade;
use App\Helpers\DateFormat;
@endphp

@extends('layouts.app')

@section('content')

<div class="uper d-flex flex-column">
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

  <div class="mb-3">
    @button_create({{url('code_gen_type')}}, {{__('code_gen_type.add')}})
  </div>

  <div class="accordion container-fluid mt-3" id="accordionFilter">

    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Filtre
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse-show" aria-labelledby="headingOne"="#accordionFilter">
        <div class="accordion-body">

          <div>
            <form method="post" action="{{ route('code_gen_type.filter') }}" enctype="multipart/form-data">

              <!-- csrf is mandatory to avoid the error 419, page expired -->
              @csrf
              <div>

                <div class="form-floating mb-2 border">
                  <input type="text" class="form-control" name="name" value="{{ old("name") }}" />
                  <label class="form-label" for="name">{{__("code_gen_type.name")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <textarea rows="4" cols="40" class="form-control" name="description">{{ old("description") }}</textarea>
                  <label class="form-label" for="description">{{__("code_gen_type.description")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <input type="text" class="form-control" name="year_of_birth" value="{{ old("year_of_birth") }}" />
                  <label class="form-label" for="year_of_birth">{{__("code_gen_type.year_of_birth")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <input type="date" class="form-control" name="birthday" value="{{ old("birthday") }}" />
                  <label class="form-label" for="birthday">{{__("code_gen_type.birthday")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <input type="time" class="form-control" name="tea_time" value="{{ old("tea_time") }}" />
                  <label class="form-label" for="tea_time">{{__("code_gen_type.tea_time")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <input type="datetime-local" class="form-control" name="takeoff" value="{{ old("takeoff") }}" />
                  <label class="form-label" for="takeoff">{{__("code_gen_type.takeoff")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <input type="text" class="form-control" name="price" value="{{ old("price") }}" />
                  <label class="form-label" for="price">{{__("code_gen_type.price")}}</label>
                </div>

                <div class="form-floating mb-2 border">
                  <fieldset class="form-group d-sm-flex flex-wrap mt-5 mb-3 ms-2">
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.redactor") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="0" class="form-check-input me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.student") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="1" class="form-check-input me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.pilot") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="2" class="form-check-input   me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.instructor") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="3" class="form-check-input   me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.winch_man") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="4" class="form-check-input   me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.tow_pilot") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="5" class="form-check-input me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.president") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="6" class="form-check-input me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.accounter") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="7" class="form-check-input me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.secretary") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="8" class="form-check-input me-3" />
                    </div>
                    <div>
                      <label for="" class="form-label">{{__("code_gen_type.qualifications.mechanic") }}</label>
                      <input type="checkbox" name="qualifications_boxes[]" value="9" class="form-check-input me-3" />
                    </div>
                  </fieldset>

                  <label class="form-label" for="qualifications">{{__("code_gen_type.qualifications")}}</label>
                </div>

                <div class="form-group mb-2 border">
                  <label class="form-label m-2" for="black_and_white">{{__("code_gen_type.black_and_white")}}</label>
                  <input type="checkbox" class="form-check-input m-2" name="black_and_white" id="black_and_white" value="1" {{old("black_and_white") ? 'checked' : ''}} />
                </div>

                <div class="form-floating mb-2 border">
                  <select class="form-select" name="color_name" id="color_name">
                    <option value="blue">{{__("code_gen_type.color_name.blue") }}</option>
                    <option value="red">{{__("code_gen_type.color_name.red") }}</option>
                    <option value="green">{{__("code_gen_type.color_name.green") }}</option>
                    <option value="white">{{__("code_gen_type.color_name.white") }}</option>
                    <option value="black">{{__("code_gen_type.color_name.black") }}</option>
                  </select>
                  <label class="form-label" for="color_name">{{__("code_gen_type.color_name")}}</label>

                </div>

                <input type="submit" name="button" value="{{__('general.filter')}}" class="btn bg-primary text-white" />
                <input type="submit" name="button" value="{{__('general.display_all')}}" class="btn bg-primary text-white" />

              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="accordion-item mb-4">
      <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Totaux
        </button>
      </h2>

      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"="#accordionFilter">
        <div class="accordion-body">
          <div>
            <table>
              <tr>
                <td align="right"> Nombre de vols = </td>
                <td> 605 </td>
                <td> Remorqué=540, Treuil=0, Autonome=50, Extérieur=15 </td>
              </tr>
              <tr>
                <td align="right"> Total heures = </td>
                <td>879h11 </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="container-fluid mb-3">
    <table class="table table-striped" id="maintable">
      <caption>{{__('code_gen_type.title')}}</caption>
      <thead>
        <tr>
          <th style="width: 30px;"></th>
          <th style="width: 30px;"></th>
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
          <th> {{__('code_gen_type.black_and_white')}} </th>
          <th> {{__('code_gen_type.color_name')}} </th>
          <th> {{__('code_gen_type.picture')}} </th>
          <th> {{__('code_gen_type.attachment')}} </th>
        </tr>
      </thead>

      <tbody>
        @foreach($code_gen_types as $code_gen_type)
        <tr>
          <td> <a href="{{ route('code_gen_type.edit', $code_gen_type->id) }}" class="btn btn-primary" dusk="edit_{{ $code_gen_type->id }}"><i class="fa-solid fa-pen-to-square"></i></a> </td>
          <td>
            <form action="{{ route("code_gen_type.destroy", $code_gen_type->id)}}" method="post">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger" type="submit" dusk="delete_{{ $code_gen_type->id }}"><i class="fa-solid fa-trash"></i></button>
            </form>
          </td>
          <td> {{$code_gen_type->name}}</td>
          <td> {{$code_gen_type->phone}}</td>
          <td> {{$code_gen_type->description}}</td>
          <td> {{$code_gen_type->year_of_birth}}</td>
          <td>
            <div align="right">{!! Blade::float($code_gen_type->weight) !!}</div>
          </td>
          <td> {{DateFormat::to_local_date($code_gen_type->birthday)}}</td>
          <td> {{$code_gen_type->tea_time}}</td>
          <td> {{DateFormat::local_datetime($code_gen_type->takeoff)}}</td>
          <td>
            <div align="right">{!! Blade::currency($code_gen_type->price) !!}</div>
          </td>
          <td>
            <div align="right">{!! Blade::currency($code_gen_type->big_price) !!}</div>
          </td>
          <td> {!! Blade::bitfield("code_gen_types", "qualifications", $code_gen_type->qualifications, "code_gen_type", ["redactor", "student", "pilot", "instructor", "winch_man", "tow_pilot", "president", "accounter", "secretary", "mechanic"]) !!}</td>
          <td> <input type="checkbox" {{ ($code_gen_type->black_and_white) ? "checked" : "" }} onclick="return false;" /></td>
          <td> {!! Blade::enumerate("code_gen_type.color_name", $code_gen_type->color_name) !!}</td>
          <td> {!! Blade::picture("code_gen_type.picture", $code_gen_type->id, "picture", $code_gen_type->picture) !!}</td>
          <td> {!! Blade::download("code_gen_type.file", $code_gen_type->id, "attachment", $code_gen_type->attachment, "Attachment") !!}</td>

        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div> <!-- content div -->
@endsection