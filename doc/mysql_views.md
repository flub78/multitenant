# MySql Views

MySQL views are another topic on which I'd like to experiment. They are definitively an elegant way to store and maintain complex selects.

Maintenance of the application could be easier if all the complex selects are stored in the database instead of being scattered across the code.

Together with the code generator they could be an efficient method to generate an application. Once the basic database schema is defined and the code to handle it is generated, it should be easy to store the application selects as MySql views. If it is possible to make the code generator aware of the views and generate the matching code it would cover a lot of the development hassles.

It implies a few things:

* The metadata layer must be able to find out when it is processing a view.
* It must be able to find out the fields subtypes and metadata from the view. Note that it is possible to enforce a name convention on the view column names to be able to retrieve the column original metadata or it can be done by analyzing the view definition.

## Remarks

If this approach is powerful it generates one controller, one model and one index view for every MySql view. For filtering fields in views it is likely more efficient to implement the filtering mechanism in the controllers. 

## How to create a MySql view with a Laravel migration

    https://www.nicesnippets.com/blog/how-to-use-mysql-view-in-laravel-8

## Views backup and restore

Views are backed up and restored with the regular mechanism. Inside the backup file, the instructions to regenerate the views are inside comments. It is a mysql feature as views are a specific to MySql and are just ignored if restored on another brand of database.

Note that json comments are also backed up and restored and it is a good thing for code generation.
