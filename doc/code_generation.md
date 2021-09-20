# Code Generation

This project extends on the Laravel idea to use php artisan to generate migrations, controllers, models, ect.

It is not only possible to generate templates with a given name, but also to adapt the template and the code inside them according to the database schema and additional metadata.

Instead of developing code for every data, developers only have to develop code for every data type. Inside an application the quantity of information may be big but the number of data types is usually much smaller. I hope to get back the time spent on putting the infrastructure in place and even to gain in productivity. (knowing perfectly that it is a big investment and sometimes the time spent in the initial effort is not compensated if the thing is not used at a sufficiently large scale).

The goal of the templating mechanism is to be able to generate quickly a working application to manage the database resources. Of course complex resources will need tuning and adaptations to make the user experience more friendly but it should work out of the box. In this context an resource may be a database table but also the result of a complex select stored as a view in the database.
     
    
## Implementation

## The database schema

Information about the existing tables, their fields and their data types are fetched from the database itself by the Schema model. This model also collects information about the existing indexes.

Note that this approach implies a very clean database schema in with all the data constraints or relationship are described in the database. For example foreign keys need to be declared. Special indexes have to be created to enforce unicity, etc. 

Another example, the field size must be exact in the database if the developer intends to limit the size of a string. The rules for string validation will be derived from the database field size and not the other way around. 

## The Metadata

The information from the schema are really useful but not sufficient to define all treatments to data types. For example a VARCHAR can be used to store a postal address, an email address or a name and in each case must be handled differently. 

The information fetched from the database schema is complemented by metadata information stored in the database itself in a table named metadata.

This mechanism will be more likely used to generate code to manage resources for the tenants as the central application has only one feature: to manage the tenants. So it is more useful to extract the schema information from the tenant database and so more logical to also store the metadata in the same database.

## Templating mechanism

The information fetched from the database is used to tune and adapt templates. Templates 
can be used to generate controllers, models, validation rules in request, factories, model unit tests and controller unit tests, in fact all type of files which differ only by the data types that they handle.

The initial implementation will be done using the mustache template package. It is a very generic template mechanism that has no dependencies to any development environment and available in multiple languages. 

To generate a file from a template the user needs to specify a template, a database table and a result destination.
Template and result can be specified as absolute file names or relative to the template and the result directory.

When the template parameter is a directory, it is parsed recursively and all the templates found are processed. It allow the generation of all the files related to a resource (a table). In this case results are stored in a directory structure that mimics the template structure.
In this case the result parameter contains template values.

Ex;
* {{filename}}              use the template basename minus the .mustache extension
* {{class}}{{filename}}     concatenation of the class name and the basename
* {{table}}/{{filename}}    result in a subdirectory named with the table name

### List of replaced patterns

* {{class_name}}        Camel case class name (model)
* {{fillable_names}}    List of fields
* 

### The template directory

It contains all the templates.

### The result directory

Contains the files after that the templates have been processed.

### The application directory

It is where the generated files are copied when the generation is over


## The php artisan command

The whole mechanism is available through a few php artisan commands:

    php artisan mustache:generate users templates result
    php artisan mustache:generate table=users files=templates/resources/views/tenants
    
    php artisan mustache:compare table files
    php artisan mustache:install files
    php artisan mustache:info table
    
    table is a database table name
    files identifies either a simple file or a directory that will be processed recursively
    
    mustache:generate process a template or a set of templates
    mustache:compare and display the differences between the generated files and the one of the application
    mustache:install copy the generated files into the application
    mustache:info just dump the metadata about one table
     
# The mustache documentation

# References

    https://faun.pub/dynamic-content-in-your-mails-using-mustache-9f3a660462ad
    https://github.com/bobthecow/mustache.php/wiki    
    
    