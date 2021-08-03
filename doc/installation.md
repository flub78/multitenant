# Multitenant application installation for development

## Pre-requisites

or initial installation steps

* A linux system
* Apache
* php version >= 8.0
* Mysql or MariaDB

## Installation steps

* Get the sources from github
* Create the tenant database
* Create a database user

* Move the public directory (some difficulties with that)
* create the .env

* migrate and seed the database
* php artisan key:generate

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


