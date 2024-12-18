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
            <p>route('calendar_event.index') = {{$route}}</p>
            <p>Central database = {{$central_db}}</p>

            <div class="d-flex flex-row">
                <div>
                    <a href="{{$url . '/api/calendar_event'}}" class="btn btn-info m-2" role="button">Calendar json API</a>
                </div>
                <div>
                    <a href="{{$url . '/api/role'}}" class="btn btn-info m-2" role="button">Role json API</a>
                </div>

            </div>
            <div class="d-flex flex-row">

                <form action="/upload_article_image" method="POST" enctype="multipart/form-data">
                    <label for="article_image" class="btn btn-primary">
                        <i class="fas fa-camera"></i> Photo
                    </label>
                    <input type="file" id="article_image" name="article_image" accept="image/*" capture="camera" style="display:none" onchange="document.getElementById('file-name').textContent = this.files.length ? this.files[0].name : ''">
                    <span id="file-name"></span>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection