    <!-- Header -->
  <div class="container-fluid p-3 bg-success text-white text-center">
    
    @if (tenant('id'))
    <h1>{{tenant('id') }}</h1>
    @else
    <h1>{{ " Central Application"}}</h1>
    @endif
  </div>
