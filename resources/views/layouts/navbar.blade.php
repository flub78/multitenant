        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
            
@auth
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                    
                    @if (tenant('id'))
                    {{__('navbar.tenant') . " = " . tenant('id') }}
                    <a href="{{ 'http://' .config('tenancy.central_domains')[0] }}" class="ml-4 text-sm text-gray-700 underline">{{__("navbar.back_to") . ' ' . config('tenancy.central_domains')[0]}}</a>
                    @else
                    {{ " Central Application"}}
                    @endif
                    
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    
                    <ul class="navbar-nav mr-auto">
@if (auth()->user()->isAdmin())    				
                    
@if (tenant('id'))
<!-- Tenant => admin dropdown -->
    					<li class="nav-item dropdown">
      						<a class="nav-link dropdown-toggle" href="#" id="navbardrop1" data-toggle="dropdown">Admin</a>
      						
      						<div class="dropdown-menu">
        						<a class="dropdown-item" href="{{ route('user.index') }}">{{__('navbar.users')}}</a>
        						<a class="dropdown-item" href="{{ route('backup.index') }}">{{__('navbar.backups')}}</a>
        						<a class="dropdown-item" href="{{ route('configuration.index') }}">{{__('navbar.configuration')}}</a>
        						<a class="dropdown-item" href="{{ route('role.index') }}">{{__('navbar.roles')}}</a>
        						<a class="dropdown-item" href="{{ route('user_role.index') }}">{{__('navbar.user_roles')}}</a>
      						</div>
    					</li>

     					<li class="nav-item dropdown">
      						<a class="nav-link dropdown-toggle" href="#" id="navbardrop2" data-toggle="dropdown">Development</a>
      						
      						<div class="dropdown-menu">
        						<a class="dropdown-item" href="{{ route('info') }}">Info</a>
        						<a class="dropdown-item" href="{{ route('test') }}">Test</a>
        						<a class="dropdown-item" href="{{ route('test.email') }}">Email</a>
        						<a class="dropdown-item" href="{{ route('metadata.index') }}">Metadata</a>
        						<a class="dropdown-item"> central database = {{env ( 'DB_DATABASE' )}} </a>
        						<a class="dropdown-item" href="{{ route('code_gen_type.index') }}">Code Gen Types</a>
      						</div>
    					</li>     					
@else
<!-- Central flat admin navbar -->
                    
    					<li class="nav-item dropdown">
      						<a class="nav-link" href="{{ route('user.index') }}" id="navbar1" >Users</a>     						
     					</li>
     					
    					<li class="nav-item dropdown">
      						<a class="nav-link" href="{{ route('tenants.index') }}" id="navbar2" >Tenants</a>     						
     					</li>
       				   
       				   	<li class="nav-item dropdown">
      						<a class="nav-link" href="{{ route('backup.index') }}" id="navbar3" >Backups</a>     						
     					</li>
   					
     					<li class="nav-item dropdown">
      						<a class="nav-link dropdown-toggle" href="#" id="navbardrop2" data-toggle="dropdown">Development</a>
      						
      						<div class="dropdown-menu">
        						<a class="dropdown-item" href="{{ route('info') }}">Info</a>
        						<a class="dropdown-item" href="{{ route('test') }}">Test</a>
        						<a class="dropdown-item" href="{{ route('test.email') }}">Email</a>
        						<a class="dropdown-item"> central database = {{env ( 'DB_DATABASE' )}} </a>
      						</div>
    					</li>     					
     					
@endif <!-- (tenant('id')) -->


       				   
@endif	<!-- (auth()->user()->isAdmin()) -->

@if (tenant('id'))

					<!-- Feature Menu items for tenants -->

    					<li class="nav-item dropdown">
      						<a class="nav-link dropdown-toggle" href="#" id="navbardrop2" data-toggle="dropdown">{{__('calendar_event.feature')}}</a>
      						
      						<div class="dropdown-menu">
        						<a class="dropdown-item" href="{{ route('calendar_event.fullcalendar') }}">{{__('calendar_event.fullcalendar')}}</a>
        						<a class="dropdown-item" href="{{ route('calendar_event.index') }}">{{__('calendar_event.list')}}</a>
        						<a class="dropdown-item" href="{{ route('calendar_event.create') }}">{{__('calendar_event.add')}}</a>
      						</div>
    					</li>

@endif <!-- (tenant('id')) -->

                    </ul>
@endauth


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
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
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" dusk="user_name" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                	
                                	<a class="dropdown-item" href="{{ route('change_password.change_password') }}" dusk="password"> {{__('user.change_password')}}</a>
                                	
                                	<a class="dropdown-item" href="{{ route('tokens.create') }}" dusk="password"> {{__('user.generate_token')}}</a>
                                	
                                    <a class="dropdown-item" href="{{ route('logout') }}" dusk="logout"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('navbar.logout') }}
                                    </a>

									
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
