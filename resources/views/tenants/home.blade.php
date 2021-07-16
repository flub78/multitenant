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
        
            <h1>{{tenant('id') . ' ' .  __('home.title')}} </h1>
        
            <div class="card">
                <div class="card-header">{{ __('home.dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{tenant('id')  }}

                    {{ __('home.logged_in') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
