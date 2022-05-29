@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif 
        @if (session('error'))
       <div class="alert alert-danger">
         <ul>
              <li>{{ session('error') }}</li>
         </ul>
        </div><br />
        @endif
        
            <h1>Mega-menu </h1>
        
             <img src="http://abbeville.tenants.com/tenancy/assets/mecanic.PNG" class="rounded-circle" alt="icon mechanique">
             <img src="{{asset('/icons/airplane.PNG')}}" 
             	class="rounded-circle" alt="icon mechanique">
        </div>
    </div>
</div>
@endsection
