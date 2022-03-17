# Code Generation

This project extends on the Laravel idea to use php artisan to generate migrations, controllers, models, etc.

Usually code templates are just files with a simple example of controller, model, htlm form to create or edit an element, and so on. The templates usaully require a significant amount of adaptation.

This generator uses data extracted from the database schema to process the templates. For example, after generation, create and edit forms contains code to input every field.

With this framework, developers can develop code for every data type instead of code for every data. Inside an application the quantity of information is usually much bigger than the number of data types. In a previous similar project I measured a ratio of 37 to 1 and I expect it to grow with the size of the application. 

It is a bet that the time spent to develop the infrastructure will be compensated by more productivity. It requires however to use the framework at a sufficiently large scale.

Generated code has often several drawbacks.
* It is usually ugly and difficult to understand
* It is often difficult to debug because of the reason above and because there is no tools working at the abstration level of the input data of the code generator
* It is often inefficient

These are the reasons which make the use of a template engine quite efficient.
* Generated code can be as clean, as well indented and commented than manual code.
* The goal being to speed up the generation of code that could have been written manually, it is usually not slower
* And if it is as clean than manual code, it is not more difficult to debug.

To some extends, the goal should be to generate code indistinguishable from manual code.

With this template mechanism it is possible to generate quickly a working application. Of course complex resources will need tuning and adaptations but a basic version should work out of the box. In this context a resource may be a database table but also the result of a joint stored as a view in the database. It is the generalization of the idea of automated CRUD generator to complex views result of select implicating several tables.

     
## Use cases

Depending on the size of the application we can expect between twenty and a few hundred resources.

As a developer 
- I want to apply one table data to one template and generate the associated code 
- I want to generate all the code for one table
- I want to compare the generated result with the current version. 
- I want to replace a current version with the generated file.

As the code generation will rarely be fully automatic there are limited needs to generate and install all files of the application.

Supported templates:
* controller
* request
* model
* factory
* create
* edit
* index
* english
* test_model
* test_controller
* test_dusk
* api
* test_api


English language files can be translated in any language supported by Google translate (French by default) using: 

    php artisan mustache:translate  --compare configuration 

## Design notes 

There is a tradeoff between the number of cases supported by the generator and its complexity. Making the generator more complex can be long and the gain in productivity becomes marginal. Keeping it too simple increase the chances to have to manually edit the result.
    
## Implementation

## The database schema

Information about the existing tables, their fields and their data types are fetched from the database itself by the Schema model. This model also collects information about the existing indexes.

This approach implies a very clean database schema in wich all the data constraints or relationship are described in the database. For example foreign keys need to be declared. Special indexes have to be created to enforce unicity, etc. 

Another example, the field size must be exact in the database if the developer intends to limit the size of a string. The rules for string validation will be derived from the database field size and not the other way around. 

## The Metadata

The information from the schema are really useful but not sufficient to define all treatments to data types. For example a VARCHAR can be used to store a postal address, an email address or a name and in each case must be handled differently. 

The information fetched from the database schema is complemented by metadata information stored in the database itself, in a table named metadata, or json encoded inside the MySql fields comments.

Keeping it inside the comments makes it more evident that the full data model is made from the schema plus additional metadata. Developers have to care about the two concepts at the same time. The only drawbacks of the storage in the comment column are, first the comment column may be used for real comments and second it may be inconvenient if the quantity and type of metadata become big. It may look like a trick but it is more convenient to store everything related to the data model in the same place. The metadata table is a little more flexible as it does not imply to migrate the database when metadata are modified. The metadata tbale is the preferred method during development. Then once the metadata are stable they should be migrated in the comment field in the associated migration.

The central application has much less feature than the tenant application. So this mechanism will be initially developed for tenants only.


### List of metadata

Here is the list of types, subtypes and metadata annotation supported by the generator.

* types
    * varchar
    * integers
    * float
    * date
    * time
    * datetime
    
* subtype
    * email
    * checkbox
    * foreign keys
    * password_with_confirmation
    * datetime_with_date_and_time
    * color
    * enumerate
    * bitfield
    * picture
    * file
    * currency
    * text (multilines)
    
* fillable
    * yes | no
    
* inTable
    * yes | no
    
* inForm
    * yes | no
    
* comment

* rules_to_add, rules_to_replace, create_rules_to_add, create_rules_to_replace, edit_rules_to_add and edit_rules_to_replace
    * list of rules to replace existing ones or be merged with the existing ones

* enumerate
    * list of possible values for the field, either a flat list, or an associative array to specify displayed values and encoding.
    
* attrs
    * a way to generate HTML attributes
    
* default
    
## Templating mechanism

The information fetched from the database is used to tune and adapt templates. Templates 
can be used to generate controllers, models, validation rules in request, factories, model unit tests and controller unit tests, in fact all type of files which differ only by the data types that they handle.

The mustache template engine has been selected for the generator. It is a very generic template mechanism that has no dependencies to any development environment and available in multiple languages. 

To generate a file from a template the user needs to specify a template, a database table and a result destination.
Template and result can be specified as absolute file names or relative to the template and the result directory.


### List of replaced patterns

This list is partial and not necessarily up to date. Look inside the templates and the CodeGenerator helper. 

    [[class_name]]              Camel case class name (model)
    [[fillable_names]]          List of fields
    [[element]]                 resource element
    [[table]]                   Database table name
    [[primary_index]]           Name of the primary index (often id)
    
    [[#select_list]]
        [[&selector]]
        [[&with]]
    [[/select_list]]
    
    [[# select_with_list ]]     
    [[/ select_with_list ]]
    
    [[# form_field_list ]]      List of fields for a form
        [[&label]]              with its label
        [[&input_create]]       create form item
        [[&input_edit]]         edit form item
        [[name]]
        [[&faker]]
    [[/ form_field_list ]]
    
    [[# table_field_list ]]     List of fields for a table
        [[element]]             
        [[name]]
    [[/ table_field_list ]]
    
    [[& button_edit ]]          Button to call the edit form
    [[& button_delete ]]        Button to delete an element
     

### Data flow

1. For each table database schema and metadata are parsed.
2. Then the mustache template engine uses these data to generate blade templates and controller or request code.
3. At runtime when a request is made, the views are generated and cached
4. Laravel code is executed, controllers, Validation, etc.

Some HTML code is static (known when the schema and metadata are known) but other is dynamic, like a select which depends on a field value to determine what option has to be marked as selected.

In the first case, the code is generated by the code generator. In the second case the code generator generates a dynamic call to a function that will be called at runtime to generate the actual code.

### The template directory

It contains all the templates, optionally in sub directories.

### The result directory

Contains the files after that the templates have been processed.

### The application directory

It is where the generated files are copied once the generated code is acceptable. The install option replace the existing version by the one generated by the code generator.


## The php artisan command

The whole mechanism is available through a few php artisan commands:

    php artisan mustache:generate users controller -v
    
    php artisan mustache:generate --compare table model
    php artisan mustache:generate --install table edit
    php artisan mustache:info calendar_events
    
table is a database table name
     
    mustache:generate process a template or a set of templates

    The compare option compares and displays the differences between the generated files and the one of the application

    the install options copies the generated files into the application

    mustache:info just dump the metadata about one table
     
# The mustache documentation

    https://faun.pub/dynamic-content-in-your-mails-using-mustache-9f3a660462ad
    https://github.com/bobthecow/mustache.php/wiki
    
# Design notes

## setters and mutators

A resource attribute is an abstract element of a resource object that can be read or set. In a lot of case there is a direct equivalence between the attribute and the way it is stored in database. For example a name is a string resource attribute which is stored directly into a varchar.

In some case it may be convenient to not store exactly what is displayed. Dates for example are stored as "Y-m-d" strings in the database but are displayed or input as date in local format. Mutators and setters are a convenient place to make this translation work. See getBirthdayAttribute and setBirthdayAttribute in CodeGenType for example.

## Derived fields

Setters and Getters are used to change the format of a field having a different representation internally and externally. In case of localization for example, the external format may depend on the current timezone, while the internal does not.

Sometimes there is no one to one relationship between the fields used to display and input an information and the way it is stored in database. For example datetime_with_date_and_time are displayed with two attributes but are stored as a unique datetime column in database.

In the same way, enumerates and bitfields are stored in a simple column but have several field
for input and display.

In these case the abstract resource has multiple attributes which are encoded together in a single column. It does not match too well with the concept of metadata, in which additional information is attached to a column to describe the resource attribute. In these cases, on piece of metadata describes multiple attribute.

It is likely not a good idea to reverse the concept and try to associate metadata with resource attribute. Trying to do that could lead to a situation requiring 100 entries to describe a bitfiled with 100 values. It is more efficient to derive the attributes, saying for example that a datetime field describes a _date and _time attribute.

In this case of multiple attribute associated to a single column, the simplest approach is to also use setters and mutators. It could be inefficient. It is better to not update the database 100 times to update a simple bitfield with 100 values.

Should ancestor and derived attributes both have accessors ? ... Or should only the derived attributes, considering that they are the only way to access the internal state ?
  
    
# Progress status


## roles table

    php artisan mustache:generate --compare roles controller        OK
    php artisan mustache:generate --compare roles request           OK
    php artisan mustache:generate --compare roles model             OK
    php artisan mustache:generate --compare roles index             OK
    php artisan mustache:generate --compare roles create            OK
    php artisan mustache:generate --compare roles edit              OK
    php artisan mustache:generate --compare roles english           OK
    php artisan mustache:generate --compare roles factory           OK
    php artisan mustache:generate --compare roles test_model        OK
    php artisan mustache:generate --compare roles test_controller   OK
    php artisan mustache:generate --compare roles test_dusk does not exist
    php artisan mustache:generate --compare roles api               OK        
    php artisan mustache:generate --compare roles test_api          ~OK

## configurations table

    php artisan mustache:generate --compare configurations controller       OK
    php artisan mustache:generate --compare configurations request          OK
    php artisan mustache:generate --compare configurations edit             OK
    php artisan mustache:generate --compare configurations create           OK
    php artisan mustache:generate --compare configurations index            OK
    php artisan mustache:generate --compare configurations model            OK
    php artisan mustache:generate --compare configurations factory          OK
    php artisan mustache:generate --compare configurations english          OK
    php artisan mustache:generate --compare configurations test_model       OK
    php artisan mustache:generate --compare configurations test_controller  OK
    php artisan mustache:generate --compare configurations test_dusk        generates something
    
## users table

    (users is not a tenant table)
    php artisan mustache:generate --compare users controller
    php artisan mustache:generate --compare users request
    php artisan mustache:generate --compare users model                     not exactly
    php artisan mustache:generate --compare users index                     OK
    php artisan mustache:generate --compare users create                    OK
    php artisan mustache:generate --compare users edit                      OK

## user_roles table

    php artisan mustache:generate --compare user_roles controller           to complete 
        missing support for user_list and role_list
        
    php artisan mustache:generate --compare user_roles request              OK
    php artisan mustache:generate --compare user_roles model
        requires attributes to access the referenced element image
        
    php artisan mustache:generate --compare user_roles index        
        user_name and role_name support missing
    php artisan mustache:generate --compare user_roles create               OK
    php artisan mustache:generate --compare user_roles edit                 OK
    php artisan mustache:generate --compare user_roles test_model           not supported yet
        requires creation of users and roles

## calendar_events table

    php artisan mustache:generate --compare calendar_events controller
        Support for dateFormat missing
    
    php artisan mustache:generate --compare calendar_events request
        missing date_format and regexp
        
    php artisan mustache:generate --compare calendar_events model
    php artisan mustache:generate --compare calendar_events index
    php artisan mustache:generate --compare calendar_events create          Almost
        support for default missing
    php artisan mustache:generate --compare calendar_events edit            Almost
        a few ids missing plus no usage of the computed attributes
        
    php artisan mustache:generate --compare calendar_events factory
    php artisan mustache:generate --compare calendar_events test_model
    
## code_gen_types table

    php artisan mustache:generate --compare code_gen_types controller           OL
    php artisan mustache:generate --compare code_gen_types request              OK          
    php artisan mustache:generate --compare code_gen_types model                OK      
    php artisan mustache:generate --compare code_gen_types index                OK
    php artisan mustache:generate --compare code_gen_types create               OK          
    php artisan mustache:generate --compare code_gen_types edit                 OK
    php artisan mustache:generate --compare code_gen_types english              OK   
    php artisan mustache:generate --compare code_gen_types factory              OK
    php artisan mustache:generate --compare code_gen_types test_model        
        one line different
        
    php artisan mustache:generate --compare code_gen_types test_controller      OK
    php artisan mustache:generate --compare code_gen_types test_dusk
        To be validated
        
    php artisan mustache:generate --compare code_gen_types api                  OK              
    php artisan mustache:generate --compare code_gen_types test_api
        to validate          ~
    