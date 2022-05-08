# Development Workflow


## General workflow

* Defines a feature using
  * As a user
  * I want to 
  * So ..
  
* Define validation scenarios
  * Given ..
  * When ..
  * Then ..
  
* Implement the validation scenarios that should not pass
  * Write the code
  * write unit tests for the code

  
# Resource CRUD workflow

For resources, the usual workflow is

* Creation of a migration
* Migration
* Include the migration into the test database
* Model and unit test
* Controller and feature test
* End-to-End test

The workflow is described in two ways, either as the usual Laravel workflow or using the code generator.

## Migration

### Manual migration creation

    php artisan make:migration Profiles
    
    then edit the migration.

### Generation of the migration from the MySQL database

#### Create the table in tenanttest
   
##### Create the table
   
![New table](images/new_table.PNG?raw=true "How to create a table")
    
##### Fill the form
    
![Table creation form](images/creation_form.PNG?raw=true "Creation form")

##### Check the indexes

![Indexes](images/indexes.PNG?raw=true "Indexes")
 
##### Create foreign key constraints

![Constraints](images/create_constraint.PNG?raw=true "Constraints")

#####  And the result must be

![Alt text](images/phpmyadmin_table_structure.PNG?raw=true "Title")

##### Generate the migration

    php artisan mustache:generate --install profiles migration
    
or

    php artisan mustache:generate --compare profiles migration
    
![WinMerge](images/WinMerge.PNG?raw=true "WinMerge")
    
### Running the migration

If the migration has been created from the table, delete the table first.

    php artisan tenants:migrate --tenants=test
    
and check that the table is the same again.

Be sure that the admin user test is registered in the test tenant. Then you can regenerate
the test database.

    php artisan --tenant=test backup:create
    php artisan --tenant=test backup:test_install

Run the tests.

## Creation of the model

[See Code generation progress](./code_generation_progress.md)

## Creation a the controller

## Creation of the views
