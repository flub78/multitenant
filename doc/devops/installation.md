# Multitenant application installation for development

## Pre-requisites

or initial installation steps

* A linux system
* Apache
* php version >= 8.0
* Mysql or MariaDB
* a domain

The domain can be public and declared in the DNS or private, in which case it must be declared in the /etc/hosts of the Ansible control node and in the target machine.

## Installation steps

### Get the sources from github

	Setup the Apache virtual host to point to the public directory.

### Create the databases

Create one database matching the credentials in each .env environement. For security reasons you should not use the database user name and password from the github repository.

A typicall development environment has 

* .env for interactive testing
* .env.testing for unit tests
* .env.dusk.local for dusk central application tests
* .env.dusk.tenants for dusk tenant application tests
* .env.dusk.deployed_tenant	for end to end test of a deployed server

Usually they use at least one database for human and one for the test, sometimes more for the test. (multi and 
multi_test)

### Migrate and seed the database

See migrations

[Migrations](../development/migrations.md)

Migrate the main database

	php artisan migrate
	
Once done it is possible to register to central application.

And create tenants :

![Create tenant](images/create_tenant.png?raw=true "Register a tenant")

![Created tenant](images/tenant_created.png?raw=true "Result of tenant creation")

Also migrate the test database
	
	

### Backup restore

It is possible to restore a backup, but it is only going to restore the central database,
not to create previous tenants.
		

### Generate a laravel key

If you are in development and already have a key in the .env files you should skip this step.

	php artisan key:generate

## Details

    1) Créer un project local
    
        cd Dropbox\xampp\htdocs
        
        composer create-project --prefer-dist laravel/laravel crud8
        http://localhost/crud8/public/ OK
    
    2) le mettre sous github
        
        Create a repo under Github (empty, no README.md, no LICENSE)
        
        https://github.com/flub78/crud8.git
                
        in cmd console
        
        git init
        git add .
        git commit -m "Empty Laravel project"
        git remote add origin https://github.com/flub78/crud8.git
        git push -u -f origin master
        
        OK, le project est sous github
        
    3) Créer le projet sous Eclipse
    
        Add a new git repo from the local directory where git init has been done
        Import the project from the git repo
        
        Create, manage and run configuration
            Add a PHP interpretor
            
        Vérifier l'exécution des test unitaires.
        
        
    4) installer le projet depuis github
    
        git clone https://github.com/flub78/crud8.git crud9
    
        cd crud9
        composer update     # to regenerate vendor directory
    
        copy .env.example   .env
        php artisan key:generate # To generate a new key
    
        http://localhost/crud9/public/  OK
    
    
    5) CRUD tutorial local
        https://appdividend.com/2020/10/13/laravel-8-crud-tutorial-example-step-by-step-from-scratch/
        
        
        cd Dropbox\xampp\htdocs
        composer create-project laravel/laravel --prefer-dist crud8
        
        http://localhost/crud8/public/
        
        http://localhost/crud8/public/games/create
    
    6) Sauvegarder CRUD tutorial sur Github
    
    7) Installer CRUD tutorial depuis github

        git clone https://github.com/flub78/crud8.git laracrud
    
        cd laracrud
        composer update     # to regenerate vendor directory
    
        copy .env.example   .env
        edit .env to st the database identifiers
        create a database
        php artisan key:generate # To generate a new key
        php artisan migrate
    
        http://localhost/laracrud/public/   OK
        http://localhost/laracrud/public/games/
        http://localhost/laracrud/public/games/create
    
    7) transformer CRUD tutorial en WEB APPs
    
    7.1 Authentication
    
        Basic authentication using Laravel UI
        
        composer require laravel/ui
        php artisan ui bootstrap
        npm install
        npm audit fix
        npm run dev (twice)
        
        php artisan ui bootstrap --auth
        npm install & npm run dev
        
        
        
        to do 
            redirect all routes to the login page when users are not logged in.
            
            Add the logout menu button on all pages
                http://localhost/crud8/public/home (has the logout button).
                
                homeController
                home.blade.php extends layout.blade.php
                class="py-4"
                
        Initial layout.blade.php
        <!-- layout.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laravel 8 CRUD Tutorial</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class="container">
    @yield('content')
  </div>
  <script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>


