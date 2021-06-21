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
        
            <h1>{{tenant('id')}}  Calendar page</h1>
        
            
            
        <div id='calendar'></div>
            
        </div>
    </div>
</div>
@endsection
