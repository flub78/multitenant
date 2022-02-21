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
- I want to generate one code file from one template for one table. 
- I want to generate all code files for one table. 
- I want to be able to compare the generated result with the current version. 
- I want to be able to replace a current version with the generated file.

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

to do:
* api_controlelr
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

The mustache template engine has been selected for the generator. It is a very generic template mechanism that has no dependencies to any development environment and available in multiple languages. 

To generate a file from a template the user needs to specify a template, a database table and a result destination.
Template and result can be specified as absolute file names or relative to the template and the result directory.


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
    
# Progress status

In the factory template the function errroneous_cases returns an empty list. It is possible to generate
erroneous cases from the validation rules (or at the same time than validation rules).

But do we need to test all the possible error cases ?
a validation rule is made of assertions to check separated by |
For most of the assertions it is possible to generate one error case.
But trying to be exhaustive there looks a little bit like trying to test the Laravel form validation mechanism.

Is it good enough to just test a few cases ?


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
    php artisan mustache:generate --compare roles api        
    php artisan mustache:generate --compare roles test_api

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
    
    