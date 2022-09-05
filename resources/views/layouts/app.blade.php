<!doctype html>
<html lang="{{App::getLocale()}}">

<head>
    @include('layouts.bs_head')
</head>

<body>
    <div id="app">
    	@include('layouts.bs_navbar')
    	@include('layouts.header')

        <main class="container-fluid p-4">
            @yield('content')
        </main>
    </div>
    
    <footer>
        @include('layouts.bs_footer')
    </footer> 
    
</body>
</html>
