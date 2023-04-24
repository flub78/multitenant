@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        
        @if (session('error'))
       <div class="alert alert-danger">
         <ul>
              <li>{{ session('error') }}</li>
         </ul>
        </div><br />
        @endif
        @if(session()->get('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}  
        </div><br />
        @endif
        
            <h1>Impression de feuilles de tickets</h1>

            <form method="post" action="{{ route('repas.csv') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-floating mb-2 border">
                <input type="file" class="form-control" name="picture" value="{{ old("picture") }}"/>
                <label class="form-label" for="picture">Fichier CSV</label>
              </div>
                                 
              @button_submit({{__('general.submit')}})
            </form>

        </div>
    </div>
</div>
@endsection
