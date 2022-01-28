# Code Generation

This project extends on the Laravel idea to use php artisan to generate migrations, controllers, models, etc.

Usually code templates are just files with a simple example of controller, model, htlm form to create or edit an element, and so on.

Here the idea is to use data extracted from the database schema to fill templates so they can be finely tuned to real data. For example create and edit forms already contains code to input every field.

With this framework, developers only have to develop code for every data type instead of code for every data. Inside an application the quantity of information may be big but the number of data types is usually much smaller. In a previous similar project the measured ratio was 37 to 1 and I expect it to grow with the size of the application. 

It is a bet that the time spent to develop the infrastructure will be compensated by more productivity during the development of the application. It is a bet, it will be only won if the framework is used at a sufficiently large scale.

The goal of this template mechanism is to generate quickly a working application to manage the database resources. Of course complex resources will need tuning and adaptations but a basic version should work out of the box. In this context a resource may be a database table but also the result of a joint stored as a view in the database. It is the generalization of the idea of automated CRUD generator to complex views result of select implicating several tables.


     
## Use cases

Depending on the size of the application we can expect between twenty and a few hundred resources.

As developer 
- I want to generate one file from one template for one table. 
- I want to generate all files from one table. 
- I want to be able to compare the generated result with the current version. 
- I want to be able to replace a current version with the generated file.

As the code generation will rarely be fully automatic there are limited needs to generate and install all files of the application.

For each resource or database table I may generate:
* a controller
* a model
* a request
* an index form
* a create form
* an edit form
* a model unit test
* a controller test
* an English language file (there is no need to generate several template files as English can perfectly be the template for others languages and automated translation is currently out of scope) Note that if this project is used one day at large scale if could be an convenient to prepare the translation files with Google translate. 

and may be some others ones ...

## Note on usage and implementation. 

There is a tradeoff between making the code generator more and more complex to handle the maximum number of cases and keeping it simple at the cost of forcing
the user to manually edit and complete the generated code. Note that the code is likely to be generated several times and if manual editions are required they will have to be perform several time. The previous sentence would push for a more complex code generator but there are limits.

Note that reaching a level where most of the code does not require manual edition worth some efforts. In this case there is no more constraints on the number of time the code is generated and re-generated. In case of manual edition it is another story and better to strictly avoid useless generations.

A field in a form can potentially use any of the validation methods supported by Laravel plus user defined validation routines.

There is relatively little added value to support has many cases in the metadata. 

To support that in a half flexible way it is possible to defined a few metadata attributes like
rules_to_add, rules_to_replace, create_rules_to_add, create_rules_to_replace, edit_rules_to_add and
edit_rules_to_replace. With these attributes it is possible to generate a request that would not require manual edition in most of the cases.

There is another tradeoff between the facility to manually edit a simple PHP request file and modifying the database schema. Modifying the schema is usually harder as it implies to migrate the various databases used in development and to regenerate the databases used for testing, not taking into account the time to debug if a schema migration has been forgotten. At the minimum these operations should be fully automated, but even automated this process will have to be maintained.

In other words, if the Request are not generated too often, it is easier to heave the code in PHP than into the schema.
 
    
## Implementation

## The database schema

Information about the existing tables, their fields and their data types are fetched from the database itself by the Schema model. This model also collects information about the existing indexes.

Note that this approach implies a very clean database schema in with all the data constraints or relationship are described in the database. For example foreign keys need to be declared. Special indexes have to be created to enforce unicity, etc. 

Another example, the field size must be exact in the database if the developer intends to limit the size of a string. The rules for string validation will be derived from the database field size and not the other way around. 

## The Metadata

The information from the schema are really useful but not sufficient to define all treatments to data types. For example a VARCHAR can be used to store a postal address, an email address or a name and in each case must be handled differently. 

The information fetched from the database schema is complemented by metadata information stored in the database itself, in a table named metadata, or json encoded inside the MySql fields comments.

Keeping it inside the comments makes it more evident that the full data model is made from the schema plus additional metadata. Developers have to care about the two concepts at the same time. The only drawbacks of the storage in the comment column are, first the comment column may be used for real comments and second it may be inconvenient if the quantity and type of metadata become big. It may look like a trick but it is more convenient to store everything related to the data model in the same place. The metadata table is a little more flexible as it does not imply to migrate the database. 

This mechanism will be more likely used to generate code to manage resources for the tenants as the central application has only one feature: tenants management. So it is more useful to extract the schema information from the tenant database and so more logical to also store the metadata in the same database.

### List of recognized metadata

* types
    * varchar
    * integers
    * float
    * date
    * time
    
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

The initial implementation will be done using the mustache template package. It is a very generic template mechanism that has no dependencies to any development environment and available in multiple languages. 

To generate a file from a template the user needs to specify a template, a database table and a result destination.
Template and result can be specified as absolute file names or relative to the template and the result directory.

When the template parameter is a directory, it is parsed recursively and all the templates found are processed. It allow the generation of all the files related to a resource (a table). In this case results are stored in a directory structure that mimics the template structure.
In this case the result parameter contains template values.

Ex;

Note it is possible to change the tag delimiters used by mustache and replace the double curly braces by something else. As these double curly braces are ambiguous in a blade templating context, we will use double square braces everywhere.

* [[filename]]              use the template basename minus the .mustache extension
* [[class]][[filename]]     concatenation of the class name and the basename
* [[table]]/[[filename]]    result in a subdirectory named with the table name

### List of replaced patterns

This is an outdated example, the source code in MetadataHelper is the reference.

* [[class_name]]        Camel case class name (model)
* [[fillable_names]]    List of fields
* 

{'class_name': 'User',
 'element': 'user',
 'field_names': ['id',
                 'name',
                 'email',
                 'password',
                 'remember_token',
                 'created_at',
                 'updated_at'],
 'fillable_names': ['name', 'email', 'password', 'remember_token'],
 'fields': [{'field_display': "[[$user['id']]]",
             'field_edit': '<input type="text" class="form-control" name="id" '
                           'id="id" value="[[ old("id") ? old("id") : '
                           '$user->id ]]">',
             'field_input': '<input type="text" class="form-control" name="id" '
                            'id="id" value="[[ old("id") ]]">',
             'label': 'Id',
             'name': 'id'},
            {'field_display': "[[$user['name']]]",
             'field_edit': '<input type="text" class="form-control" '
                           'name="name" id="name" value="[[ old("name") ? '
                           'old("name") : $user->name ]]">',
             'field_input': '<input type="text" class="form-control" '
                            'name="name" id="name" value="[[ old("name") ]]">',
             'label': 'Name',
             'name': 'name'},
            {'field_display': "[[$user['email']]]",
             'field_edit': '<input type="text" class="form-control" '
                           'name="email" id="email" value="[[ old("email") ? '
                           'old("email") : $user->email ]]">',
             'field_input': '<input type="text" class="form-control" '
                            'name="email" id="email" value="[[ old("email") '
                            ']]">',
             'label': 'Email',
             'name': 'email'},
            {'field_display': "[[$user['password']]]",
             'field_edit': '<input type="password" class="form-control" '
                           'name="password" id="password" value="[[ '
                           'old("password") ? old("password") : '
                           '$user->password ]]">',
             'field_input': '<input type="password" class="form-control" '
                            'name="password" id="password" value="[[ '
                            'old("password") ]]">',
             'label': 'Password',
             'name': 'password'},
            {'field_display': "[[$user['remember_token']]]",
             'field_edit': '<input type="password" class="form-control" '
                           'name="remember_token" id="remember_token" '
                           'value="[[ old("remember_token") ? '
                           'old("remember_token") : $user->remember_token ]]">',
             'field_input': '<input type="password" class="form-control" '
                            'name="remember_token" id="remember_token" '
                            'value="[[ old("remember_token") ]]">',
             'label': 'Remember_token',
             'name': 'remember_token'},
            {'field_display': "[[$user['created_at']]]",
             'field_edit': '<input type="text" class="form-control" '
                           'name="created_at" id="created_at" value="[[ '
                           'old("created_at") ? old("created_at") : '
                           '$user->created_at ]]">',
             'field_input': '<input type="text" class="form-control" '
                            'name="created_at" id="created_at" value="[[ '
                            'old("created_at") ]]">',
             'label': 'Created_at',
             'name': 'created_at'},
            {'field_display': "[[$user['updated_at']]]",
             'field_edit': '<input type="text" class="form-control" '
                           'name="updated_at" id="updated_at" value="[[ '
                           'old("updated_at") ? old("updated_at") : '
                           '$user->updated_at ]]">',
             'field_input': '<input type="text" class="form-control" '
                            'name="updated_at" id="updated_at" value="[[ '
                            'old("updated_at") ]]">',
             'label': 'Updated_at',
             'name': 'updated_at'}],
 'fillable': [{'field_display': "[[$user['name']]]",
               'field_edit': '<input type="text" class="form-control" '
                             'name="name" id="name" value="[[ old("name") ? '
                             'old("name") : $user->name ]]">',
               'field_input': '<input type="text" class="form-control" '
                              'name="name" id="name" value="[[ old("name") '
                              ']]">',
               'label': 'Name',
               'name': 'name'},
              {'field_display': "[[$user['email']]]",
               'field_edit': '<input type="text" class="form-control" '
                             'name="email" id="email" value="[[ old("email") ? '
                             'old("email") : $user->email ]]">',
               'field_input': '<input type="text" class="form-control" '
                              'name="email" id="email" value="[[ old("email") '
                              ']]">',
               'label': 'Email',
               'name': 'email'},
              {'field_display': "[[$user['password']]]",
               'field_edit': '<input type="password" class="form-control" '
                             'name="password" id="password" value="[[ '
                             'old("password") ? old("password") : '
                             '$user->password ]]">',
               'field_input': '<input type="password" class="form-control" '
                              'name="password" id="password" value="[[ '
                              'old("password") ]]">',
               'label': 'Password',
               'name': 'password'},
              {'field_display': "[[$user['remember_token']]]",
               'field_edit': '<input type="password" class="form-control" '
                             'name="remember_token" id="remember_token" '
                             'value="[[ old("remember_token") ? '
                             'old("remember_token") : $user->remember_token '
                             ']]">',
               'field_input': '<input type="password" class="form-control" '
                              'name="remember_token" id="remember_token" '
                              'value="[[ old("remember_token") ]]">',
               'label': 'Remember_token',
               'name': 'remember_token'}],
 'lang': [],
 'list': [],
 'table': 'users'}
```
### Data flow

1. For each table database schema and metadata are parsed.
2. Then the mustache template engine uses these data to generate blade templates and controller or rquest code.
3. At runtime when a request is made, the views are generated and cached
4. Laravel code is executed, controllers, Validation, etc.

Note that some HTML code is static (known when the schema and metadata are known) but other is dynamic, like a select which depends on a field value to determine what option has to be marked as selected.

In the firt case, the code is generated by the code generator. In the second case the code generator generates a dynamic call to a function that will be called at runtime to generate the actual code.

With two layers of templates I wonder if this approach is not a little over engineered ... But the alternatives would imply either very complex blade pre-processing or dynamic generation at runtime which should be avoided.

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
    
# Progress

## roles table

    php artisan mustache:generate --compare roles controller        OK
    php artisan mustache:generate --compare roles request           OK
    php artisan mustache:generate --compare roles model             To do
    php artisan mustache:generate --compare roles index             OK
    php artisan mustache:generate --compare roles create            OK
    php artisan mustache:generate --compare roles edit              OK
    

## configurations table

    php artisan mustache:generate --compare configurations controller       OK
    php artisan mustache:generate --compare configurations request          OK
    php artisan mustache:generate --compare configurations edit             OK
    php artisan mustache:generate --compare configurations create           OK
    php artisan mustache:generate --compare configurations index            OK
    php artisan mustache:generate --compare configurations model
    php artisan mustache:generate --compare configurations english
    php artisan mustache:generate --compare configurations test_model
    php artisan mustache:generate --compare configurations test_controller
    php artisan mustache:generate --compare configurations test_dusk
    
## users table

    php artisan mustache:generate --compare users controller
    php artisan mustache:generate --compare users request
    php artisan mustache:generate --compare users model                     not exactly
    php artisan mustache:generate --compare users index                     OK
    php artisan mustache:generate --compare users create                    OK
    php artisan mustache:generate --compare users edit                      OK

## user_roles table

    php artisan mustache:generate --compare user_roles controller       
        missing support for user_list and role_list
    php artisan mustache:generate --compare user_roles request              OK
    php artisan mustache:generate --compare user_roles model
    php artisan mustache:generate --compare user_roles index        
        user_name and role_name support missing
    php artisan mustache:generate --compare user_roles create               OK
    php artisan mustache:generate --compare user_roles edit                 OK

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
    