# MySql Views

MySQL are an elegant way to store and maintain complex selects.

Maintenance of the application could be easier if all the complex selects are stored in the database instead of being scattered across the code.

Together with the code generator they are an efficient method to generate an application. Once the basic database schema is defined and the code to handle it is generated, it is easy to store the application selects as MySql views. The code generator aware of the views and can generate the matching code.

## Remarks

If this approach is powerful it generates one controller, one model and one index view for every MySql view. For filtering fields in views it is likely more efficient to implement the filtering mechanism in the controllers. 

## How to create a MySql view with a Laravel migration

[How to use MySQL views in Laravel](https://www.nicesnippets.com/blog/how-to-use-mysql-view-in-laravel-8)

### Generate a migration

### Create the view with phpmysadmin

### Update the migration and migrate

## Views backup and restore

Views are backed up and restored with the regular mechanism. Inside the backup file, the instructions to regenerate the views are inside comments. It is a mysql feature as views are a specific to MySql and are just ignored if restored on another brand of database.

Note that json comments are also backed up and restored and it is a good thing for code generation.
