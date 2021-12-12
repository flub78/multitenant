# Current development status

## DevOps

### LinuxMint Jenkins server

* Static analysis job 
* Unit tests and feature tests
    - With test coverage , about 90 % of coverage
* Dusk central tests
* Tenant Central tests

### AWS jenkins server

* flub78.net
* Manual test deployment for central and tenants central.flub78.net and abbeville.central.flub78.net
* Static analysis job
* Unit tests and feature tests
    - without coverage 
* Dusk tests are not running (difficulties to deploy chrome driver)

### Todo

* complete CI/CD pipeline 
    - especially automated deployment
    - and End to End tests of an external server

# Supported Features

## For Central and for tenants

* Generic look and feel
    - datatable with pagination and search
    - Print, CVS export, PDF export
    
* Localisation
    
* User management and authentication
    - Login and Logout
    - User creation, registration, modification, delete
    - Forgotten password
    - Active and not active users
    - admin users
    
* Database backup and restore
    - backup download and upload
    - list of local backups
    - make a local backup
    - restore and delete local backups
     
* Devlopment support
    - phpinfo
    - test page
    

## For central

* Tenant management
    - tenant creation, it creates the database and storage
    

## For tenants

* Tenant configuration support
    - Key value specific to each tenant

* Role management
    - role CRUD
    - assignement of a role to a user
    
    - to do: change behavio according to role
    
* Calendar support in progress

## Todo

    See Trello list of stories
    https://trello.com/b/VGGQo4XL/webapp
