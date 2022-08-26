  <!-- Navbar -->
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark pb-3 fixed-top" style="position: sticky;">
    <div class="container-fluid">

      <a class="navbar-brand" href="javascript:void(0)">{{ config('app.name', 'Laravel') }}</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav me-auto"> <!-- left side of the navbar -->
        
          @if (tenant('id'))
          <li class="nav-item">
          	<a href="{{ 'http://' .config('tenancy.central_domains')[0] }}" class="nav-link ">{{__("navbar.back_to") . ' ' . config('tenancy.central_domains')[0]}}</a>
		  </li>
		  @endif

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dropdown</a>
            <ul class="dropdown-menu">
            
              <li><a class="dropdown-item" href="#">Link</a></li>
              <li><a class="dropdown-item" href="#">Another link</a></li>
              <li><a class="dropdown-item" href="#">A third link</a></li>

            </ul>
          </li>
          
          @if (auth()->user()->isAdmin())
          @if (tenant('id'))
          <!-- admin for tenant -->
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('user.index') }}">{{__('navbar.users')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('backup.index') }}">{{__('navbar.backups')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('configuration.index') }}">{{__('navbar.configuration')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('role.index') }}">{{__('navbar.roles')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('user_role.index') }}">{{__('navbar.user_roles')}}</a></li>
            </ul>
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Development</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('info') }}">Info</a></li>
              <li><a class="dropdown-item" href="{{ route('test') }}">Test</a></li>
              <li><a class="dropdown-item" href="{{ route('test.email') }}">Email</a></li>
              <li><a class="dropdown-item" href="{{ route('metadata.index') }}">Metadata</a></li>
              <li><a class="dropdown-item" href="#">central database = {{env ( 'DB_DATABASE' )}}</a></li>
              <li><a class="dropdown-item" href="{{ route('code_gen_type.index') }}">Code Gen Types</a></li>
            </ul>
          </li>
          
          @else
          <!-- admin for central application -->
          <!-- Central flat admin navbar -->
          
          <li class="nav-item">
          	<a href="{{ route('user.index') }}" class="nav-link ">Users</a>
		  </li>
          <li class="nav-item">
          	<a href="{{ route('tenants.index') }}" class="nav-link ">Tenants</a>
		  </li>
          <li class="nav-item">
          	<a href="{{ route('backup.index') }}" class="nav-link ">Backups</a>
		  </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Development</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('info') }}">Info</a></li>
              <li><a class="dropdown-item" href="{{ route('test') }}">Test</a></li>
              <li><a class="dropdown-item" href="{{ route('test.email') }}">Email</a></li>
              <li><a class="dropdown-item" href="#">central database = {{env ( 'DB_DATABASE' )}}</a></li>
            </ul>
          </li>
          
          @endif <!-- (tenant('id')) -->
          @endif <!-- (auth()->user()->isAdmin()) -->
          
        </ul>

        <form class="d-flex"> <!-- right side of the navbar -->
          <input class="form-control me-2" type="text" placeholder="Search">
          <button class="btn btn-primary" type="button">Search</button>
          <div class="text-white p-2">Frédéric_Peignot</div>
          <div class="text-white p-2">Admin</div>

          <li class="nav-item dropdown dropstart">
            <a class="nav-link dropdown-toggle  text-white" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-user fa-2xl"></i>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Change password</a></li>
              <li><a class="dropdown-item" href="#">Exit</a></li>
            </ul>
          </li>

        </form>
      </div>
    </div>
  </nav>
  
    <!-- Header -->
  <div class="container-fluid p-3 bg-success text-white text-center">
    
    @if (tenant('id'))
    <h1>{{tenant('id') }}</h1>
    @else
    <h1>{{ " Central Application"}}</h1>
    @endif
  </div>
  
