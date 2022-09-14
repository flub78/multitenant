  <header class="container-fluid p-3 bg-success text-white text-center d-flex flex-row justify-content-between">
    
    @if (tenant('id'))
    <div id="header_left"></div>
    <h1>{{tenant('id') }}</h1>
    <div id="header_right"></div>
    @else
    <h1>{{ " Central Application"}}</h1>
    @endif
  </header>
