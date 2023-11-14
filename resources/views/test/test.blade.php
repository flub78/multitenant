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
        
            <h1>Test page</h1>
            <div class="card mb-3">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}                    
                </div>
            </div>
            
            <h3>Tenant Test Controller</h3>
            <p>This page should be disabled in deployment. </p>
            <p>Configuration locale = {{$locale}}</p>
            <p>Application locale = {{$app_locale}}</p>
            <p>url = {{$url}}</p>
            <p>route('calendar_event.index')  = {{$route}}</p>
            <p>Central database  = {{$central_db}}</p>
            
            <div class="d-flex flex-row">
            	<div>
            	<a href="http://abbeville.tenants.com/api/calendar_event" class="btn btn-info m-2" role="button">Calendar json API</a>
				</div><div>
            	<a href="http://abbeville.tenants.com/api/role" class="btn btn-info m-2" role="button">Role json API</a>
				</div><div>
            	<a href="http://abbeville.tenants.com/api/calendar_event" class="btn btn-info m-2" role="button">Calendar json API</a>
				</div><div>
            	<a href="http://abbeville.tenants.com/api/calendar_event" class="btn btn-info m-2" role="button">Calendar json API</a>
				</div><div>
            	<a href="http://abbeville.tenants.com/api/calendar_event" class="btn btn-info m-2" role="button">Calendar json API</a>
				</div><div>
            	<a href="http://abbeville.tenants.com/api/calendar_event" class="btn btn-info m-2" role="button">Calendar json API</a>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
