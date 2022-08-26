<!doctype html>
<html lang="{{App::getLocale()}}">

<head>
    @include('layouts.bs_header')
</head>

<body>
    <div id="app">
    	@include('layouts.bs_navbar')

        <main class="container py-4">
            @yield('content')
        </main>
    </div>
    
    <footer>
        @include('layouts.bs_footer')
    </footer> 
    
</body>
</html>
