  <!-- Navbar -->
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark pb-3 fixed-top" style="position: sticky;">
    <div class="container-fluid">

      <a class="navbar-brand" href="javascript:void(0)">{{ config('app.name', 'Laravel') }}</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav me-auto"> <!-- left side of the navbar -->

          @auth

          @if (tenant('id'))

          <li class="nav-item">

            @php
            if (array_key_exists("SERVER_PORT",$_SERVER)) {
            $SERVER_PORT = $_SERVER['SERVER_PORT'];
            } else {
            $SERVER_PORT = "8000";
            }
            @endphp


            <a href="{{ 'http://' . config('tenancy.central_domains')[0] . ':' . $SERVER_PORT }}" class="nav-link ">Central</a>
          </li>
          @endif

          <!--  Exemple of dropdown menu 
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dropdown</a>
            <ul class="dropdown-menu">
            
              <li><a class="dropdown-item" href="#">Link</a></li>
              <li><a class="dropdown-item" href="#">Another link</a></li>
              <li><a class="dropdown-item" href="#">A third link</a></li>

            </ul>
          </li>
          -->

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
              <li><a class="dropdown-item" href="{{ route('motd.index') }}">{{__('navbar.motd')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('motd_today.index') }}">{{__('navbar.motd_today')}}</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Development</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('info') }}">Info</a></li>
              <li><a class="dropdown-item" href="{{ route('test') }}">Test</a></li>
              <li><a class="dropdown-item" href="{{ route('test.email') }}">Email (if smtp)</a></li>
              <li><a class="dropdown-item" href="{{ route('metadata.index') }}">Metadata</a></li>
              <li><a class="dropdown-item" href="{{ route('code_gen_type.index') }}">Code Gen Types</a></li>
              <li><a class="dropdown-item" href="{{ route('code_gen_types_view1') }}">Code Gen Types View</a></li>
              <li><a class="dropdown-item" href="{{ route('test.checklist') }}">Manual resource testing checklist</a></li>
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

          @if (tenant('id'))

          <!-- Feature Menu items for tenants -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{__('calendar_event.feature')}}</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('calendar_event.fullcalendar') }}">{{__('calendar_event.fullcalendar')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('calendar_event.index') }}">{{__('calendar_event.list')}}</a></li>
              <li><a class="dropdown-item" href="{{ route('calendar_event.create') }}">{{__('calendar_event.add')}}</a></li>
            </ul>
          </li>

          @endif <!-- (tenant('id')) -->

        </ul>

        <form class="d-flex"> <!-- right side of the navbar -->
          <input class="form-control me-2" type="text" placeholder="Search">
          <button class="btn btn-primary" type="button">Search</button>
        </form>


        <li class="nav-item dropdown  ">
          <a class="nav-link dropdown-toggle  text-white" href="#" role="button" data-bs-toggle="dropdown" dusk="user_name">
            {{ Auth::user()->name }}
            <i class="fa-solid fa-user fa-2xl"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-sm-end">
            <li><a class="dropdown-item" href="{{ route('change_password.change_password') }}">Change password</a></li>
            <li><a class="dropdown-item" href="{{ route('personal_access_token.index') }}">{{__('user.tokens')}}</a></li>
            <li><a class="dropdown-item" href="{{ route('tokens.create') }}">{{__('user.generate_token')}}</a></li>

            <li><a class="dropdown-item" href="{{ route('logout') }}" dusk="logout"
                onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                {{ __('navbar.logout') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          </ul>
        </li>
        @endauth

        @guest
        @if (Route::has('login'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
        @endif

        @if (Route::has('register'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        </li>
        @endif
        @endguest

      </div>

    </div>
  </nav>