# Naming conventions

In this project, naming conventions are important, not only to insure homogeneous naming but also to minimize surprises and to enforce the convention over configuration principle. This principle is heavily used by Ruby on rail. It implies that in most of the cases, reasonable default are provided and configuration has only to be used when the default is not acceptable. It make the programmer life easier as in most of the case, there is nothing to do.

Note that the code generation mechanism expects these conventions to be used and following them minimize the manual modification of the generated code.

## Naming convention for Laravel Models

Models have singular names starting with upper case (CamelCase) 

    php artisan make:model --all Tenants\Role
        -a, --all             Generate a migration, seeder, factory, and resource controller for the model
    
    php artisan make:model --all Tenants\Role
    
    Model created successfully.
        app/Models/Tenant/Role.php
        
    Factory created successfully.
        databases/factories/Tenants/RoleFactory
        
    Created Migration: 2021_08_06_113542_create_roles_table
        databases/migration/2021_08_06_113542_create_roles_table.php to move into tenant
        
    Seeder created successfully.
        databases/seeders
        
    Controller created successfully.
        controllers to be moved into Tenants
    
    
    
## Naming conventions for databases

* table names:      plural names lower case separated by underscores (snake_case)
* column names:     snake_case
* primary key name: id

Model and table have the same name in different casing. (Role versus roles, CalendarEvent versus calendar_event).


## Naming convention for resources model classes

methods are in lowerCamelCase

getters and setters are in lowerCamelCase getStartTime.

## Naming convention for route resources

By convention route to access resources use the element name (singular of the database table name). 

## Naming convention for PHP