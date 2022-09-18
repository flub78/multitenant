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
  
            <div class="accordion container-fluid mt-3" id="accordionExample">

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                  aria-expanded="true" aria-controls="collapseOne">
                  Filtre
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse"
                aria-labelledby="headingOne"="#accordionExample">
                <div class="accordion-body">
                
                  <div>
                    <form action="https://gvv.planeur-abbeville.fr/index.php/vols_planeur/filterValidation/3"
                      method="post" accept-charset="utf-8" name="saisie">
                      <div>

                        <div>
                          <label for="date" class="form-label">Date: </label>
                          <input type="date" name="filter_date" value="" size="15" title="JJ/MM/AAAA"
                            class="datepicker form-control" id="date" />
                        </div>

                        <div>
                          <label for="until" class="form-label">Jusqu'a: </label>
                          <input type="date" name="date_end" value="" size="15" title="JJ/MM/AAAA"
                            class="datepicker form-control" id="until" />
                        </div>

                        <input type="submit" name="button" value="Filtrer" class="btn bg-primary text-white" />
                        <input type="submit" name="button" value="Afficher tout" class="btn bg-primary text-white" />

                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>

            <div class="accordion-item mb-4">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Totaux
                </button>
              </h2>
              
              <div id="collapseTwo" class="accordion-collapse collapse"
                aria-labelledby="headingTwo"="#accordionExample">
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
  <table class="table table-striped"  id="maintable">
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
          <td> <a href="{{ route('code_gen_type.edit', $code_gen_type->id) }}" class="btn btn-primary" dusk="edit_{{ $code_gen_type->id }}"><i class="fa-solid fa-pen-to-square"></i></a>  </td>
          <td> <form action="{{ route("code_gen_type.destroy", $code_gen_type->id)}}" method="post">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-danger" type="submit" dusk="delete_{{ $code_gen_type->id }}"><i class="fa-solid fa-trash"></i></button>
                 </form>
 		  </td>
          <td> {{$code_gen_type->name}}</td>
          <td> {{$code_gen_type->phone}}</td>
          <td> {{$code_gen_type->description}}</td>
          <td> {{$code_gen_type->year_of_birth}}</td>
          <td> <div align="right">{!! Blade::float($code_gen_type->weight) !!}</div></td>
          <td> {{DateFormat::to_local_date($code_gen_type->birthday)}}</td>
          <td> {{$code_gen_type->tea_time}}</td>
          <td> {{DateFormat::local_datetime($code_gen_type->takeoff)}}</td>
          <td> <div align="right">{!! Blade::currency($code_gen_type->price) !!}</div></td>
          <td> <div align="right">{!! Blade::currency($code_gen_type->big_price) !!}</div></td>
          <td> {!! Blade::bitfield("code_gen_types", "qualifications", $code_gen_type->qualifications, "code_gen_type", ["redactor", "student", "pilot", "instructor", "winch_man", "tow_pilot", "president", "accounter", "secretary", "mechanic"]) !!}</td>
          <td> <input type="checkbox" {{ ($code_gen_type->black_and_white) ? "checked" : "" }}  onclick="return false;" /></td>
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


