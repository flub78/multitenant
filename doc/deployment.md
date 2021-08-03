# Multitenant WEB application deployment

Laravel deployment is not the simplest thing in the world (at least compared to CodeIgniter). The recommended way implies composer, or docker containers which may be convenient for big organizations but are not really simple for simple projects.

It is recommended to deploy Laravel on Unix machine on which you have full control and even that is not simple.

## References

    https://laravel.com/docs/8.x/deployment
    
    https://laraveldaily.com/how-to-deploy-laravel-projects-to-live-server-the-ultimate-guide/
    
    https://www.digitalocean.com/community/tutorials/how-to-run-multiple-php-versions-on-one-server-using-apache-and-php-fpm-on-ubuntu-18-04-fr    
    
    Yes, you can use Laravel on shared-hosting. But WHY? https://www.youtube.com/watch?v=qHw3Gl33GZI
    
The main reference for my deployment:

    https://phpraxis.wordpress.com/2016/08/02/steps-for-configuring-laravel-on-apache-http-server/

## deployment
 

### Install PHP, Apache HTTP server, and MySQL.

This project requires PHP 8.x. SO if the server is already used for PHP7 projects, both of them must be installed.

The following tutorial explains how to install several versions of PHP.

    https://www.digitalocean.com/community/tutorials/how-to-run-multiple-php-versions-on-one-server-using-apache-and-php-fpm-on-ubuntu-18-04-fr
    
    sudo apt-get install php8.0-xml
    1294  apt-get install php8.0-xml
    
    1295  a2enmod proxy_fcgi setenvif
    1296  a2enconf php8.0-fpm                       Not compatible with the installed phpmyadmin
    1297  systemctl reload apache2
    
    sudo apt-get install software-properties-common -y
    sudo add-apt-repository ppa:ondrej/php
    sudo add-apt-repository ppa:ondrej/apache2
    sudo apt-get update -y
    sudo apt-get install php7.3 php7.3-fpm php7.3-mysql libapache2-mod-php7.3 libapache2-mod-fcgid -y
    sudo apt-get install php8.0 php8.0-fpm php8.0-mysql libapache2-mod-php8.0 libapache2-mod-fcgid -y
    
    Please provide a valid cache path
    /usr/bin/php8.0 artisan list
    /usr/bin/php8.0 artisan list

    InvalidArgumentException

    Please provide a valid cache path.
  
    Try the following:

    create these folders under storage/framework:

        sessions
        views
        cache
        
### Enable mod_rewrite for Apache.
        done
        
### Install Composer globally.
        I'll try to avoid by saving the vendor subdirectory under git
        
### Create your Laravel project in user directory.
        git clone https://github.com/flub78/multitenant.git
        
        source /usr/local/bin/set_php8
        php --version
        PHP 8.0.8 (cli) (built: Jul  1 2021 15:26:28) ( NTS )
        
        mkdir storage/framework
        mkdir storage/framework/sessions
        mkdir storage/framework/views
        mkdir storage/framework/cache
        mkdir storage/app
        mkdir storage/app/backup
        php artisan     OK
        
        cp .env.example .env
        php artisan key:generate

### Set permissions for Laravel folders.
    
        chmod -R 777 storage bootstrap/cache
        
### Set up an Apache virtual host for your Laravel project.
    
    sudo a2dissite tenants.com
    sudo a2ensite tenants.com
    sudo systemctl reload apache2
    
    sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/laravel.conf
    sudo vi /etc/apache2/sites-available/laravel.conf
    
    sudo a2ensite laravel.conf
    sudo systemctl reload apache2
    
    Add new virtual host to hosts file.
        done
    
    Enable new virtual host and (optionally) disable the default virtual host.
        done
    [Optional] Install Node.JS JavaScript engine, which is required for Laravel Elixir.
    [Optional] Create a MySQL (or SQLite) database to use with your Laravel project.
    
        set correct database id and password in .env


    http://tenants.com:8888
    Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 7.3.0". 
    
    <FilesMatch \.php$>
      # For Apache version 2.4.10 and above, use SetHandler to run PHP as a fastCGI process server
      SetHandler "proxy:unix:/run/php/php8.0-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    It works...

## Deployment on a shared hosting (attempt)

I already have a shared hosting used for others projects. Let's try it.

It has a ssh access, the capacity to create MySql databases and to select the PHP version.

I want to use sub sub domains.

    central.app.domain
    
    tenant1.app.domain
    
It is probably difficult/impossible to install composer or nodejs.

