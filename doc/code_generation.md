# Code Generation

This project extends on the Laravel idea to use php artisan to generate migrations, controllers, models, ect.

It is not only possible to generate templates with a given name, but also to adapt the template and the code inside them according to the database schema and additional metadata.

Instead of developing code for every data, developers only have to develop code for every data type. Inside an application the quantity of information may be big but the number of data types is usually much smaller. I hope to get back the time spent on putting the infrastructure in place and even to gain in productivity. (knowing perfectly that it is a big investment and sometimes the time spent in the initial effort is not compensated if the thing is not used at a sufficiently large scale).

The goal of the templating mechanism is to be able to generate quickly a working application to manage the database resources. Of course complex resources will need tuning and adaptations to make the user experience more friendly but it should work out of the box. In this context an resource may be a database table but also the result of a complex select stored as a view in the database.
     
## Use cases

Depending on the size of the application we can expect between twenty and a few hundred resources.

As developer I want to be able to generate one file from one template for one table. I want to be able to generate all files from one table. I want to be able to compare the generated result with the current version. I want to be able to replace a current version with the generated file.

As the code generation will rarely be fully automatic there are limited needs to generate and install all files of the application.

For each resource I may have to generate:
* a controller
* a model
* a request
* an index form
* a create form
* an edit form
* a model unit test
* a controller test
* an English language file
* a template for French language
and may be some others ones ...
    
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

This is an outdated example, the source code in MetadataHelper is the reference.

* {{class_name}}        Camel case class name (model)
* {{fillable_names}}    List of fields
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
 'fields': [{'field_display': "{{$user['id']}}",
             'field_edit': '<input type="text" class="form-control" name="id" '
                           'id="id" value="{{ old("id") ? old("id") : '
                           '$user->id }}">',
             'field_input': '<input type="text" class="form-control" name="id" '
                            'id="id" value="{{ old("id") }}">',
             'label': 'Id',
             'name': 'id'},
            {'field_display': "{{$user['name']}}",
             'field_edit': '<input type="text" class="form-control" '
                           'name="name" id="name" value="{{ old("name") ? '
                           'old("name") : $user->name }}">',
             'field_input': '<input type="text" class="form-control" '
                            'name="name" id="name" value="{{ old("name") }}">',
             'label': 'Name',
             'name': 'name'},
            {'field_display': "{{$user['email']}}",
             'field_edit': '<input type="text" class="form-control" '
                           'name="email" id="email" value="{{ old("email") ? '
                           'old("email") : $user->email }}">',
             'field_input': '<input type="text" class="form-control" '
                            'name="email" id="email" value="{{ old("email") '
                            '}}">',
             'label': 'Email',
             'name': 'email'},
            {'field_display': "{{$user['password']}}",
             'field_edit': '<input type="password" class="form-control" '
                           'name="password" id="password" value="{{ '
                           'old("password") ? old("password") : '
                           '$user->password }}">',
             'field_input': '<input type="password" class="form-control" '
                            'name="password" id="password" value="{{ '
                            'old("password") }}">',
             'label': 'Password',
             'name': 'password'},
            {'field_display': "{{$user['remember_token']}}",
             'field_edit': '<input type="password" class="form-control" '
                           'name="remember_token" id="remember_token" '
                           'value="{{ old("remember_token") ? '
                           'old("remember_token") : $user->remember_token }}">',
             'field_input': '<input type="password" class="form-control" '
                            'name="remember_token" id="remember_token" '
                            'value="{{ old("remember_token") }}">',
             'label': 'Remember_token',
             'name': 'remember_token'},
            {'field_display': "{{$user['created_at']}}",
             'field_edit': '<input type="text" class="form-control" '
                           'name="created_at" id="created_at" value="{{ '
                           'old("created_at") ? old("created_at") : '
                           '$user->created_at }}">',
             'field_input': '<input type="text" class="form-control" '
                            'name="created_at" id="created_at" value="{{ '
                            'old("created_at") }}">',
             'label': 'Created_at',
             'name': 'created_at'},
            {'field_display': "{{$user['updated_at']}}",
             'field_edit': '<input type="text" class="form-control" '
                           'name="updated_at" id="updated_at" value="{{ '
                           'old("updated_at") ? old("updated_at") : '
                           '$user->updated_at }}">',
             'field_input': '<input type="text" class="form-control" '
                            'name="updated_at" id="updated_at" value="{{ '
                            'old("updated_at") }}">',
             'label': 'Updated_at',
             'name': 'updated_at'}],
 'fillable': [{'field_display': "{{$user['name']}}",
               'field_edit': '<input type="text" class="form-control" '
                             'name="name" id="name" value="{{ old("name") ? '
                             'old("name") : $user->name }}">',
               'field_input': '<input type="text" class="form-control" '
                              'name="name" id="name" value="{{ old("name") '
                              '}}">',
               'label': 'Name',
               'name': 'name'},
              {'field_display': "{{$user['email']}}",
               'field_edit': '<input type="text" class="form-control" '
                             'name="email" id="email" value="{{ old("email") ? '
                             'old("email") : $user->email }}">',
               'field_input': '<input type="text" class="form-control" '
                              'name="email" id="email" value="{{ old("email") '
                              '}}">',
               'label': 'Email',
               'name': 'email'},
              {'field_display': "{{$user['password']}}",
               'field_edit': '<input type="password" class="form-control" '
                             'name="password" id="password" value="{{ '
                             'old("password") ? old("password") : '
                             '$user->password }}">',
               'field_input': '<input type="password" class="form-control" '
                              'name="password" id="password" value="{{ '
                              'old("password") }}">',
               'label': 'Password',
               'name': 'password'},
              {'field_display': "{{$user['remember_token']}}",
               'field_edit': '<input type="password" class="form-control" '
                             'name="remember_token" id="remember_token" '
                             'value="{{ old("remember_token") ? '
                             'old("remember_token") : $user->remember_token '
                             '}}">',
               'field_input': '<input type="password" class="form-control" '
                              'name="remember_token" id="remember_token" '
                              'value="{{ old("remember_token") }}">',
               'label': 'Remember_token',
               'name': 'remember_token'}],
 'lang': [],
 'list': [],
 'table': 'users'}
```

### The template directory

It contains all the templates, optionally in sub directories.

### The result directory

Contains the files after that the templates have been processed.

### The application directory

It is where the generated files are copied when the generation is over


## The php artisan command

The whole mechanism is available through a few php artisan commands:

    php artisan mustache:generate users controller
    
    php artisan mustache:generate --compare table model
    php artisan mustache:generate --install table edit
    php artisan mustache:info table
    
    table is a database table name
     
    mustache:generate process a template or a set of templates
    The compare option compares and displays the differences between the generated files and the one of the application
    the install options copies the generated files into the application
    mustache:info just dump the metadata about one table
     
# The mustache documentation

# References

    https://faun.pub/dynamic-content-in-your-mails-using-mustache-9f3a660462ad
    https://github.com/bobthecow/mustache.php/wiki    
    
# Progress

## Resources 

roles

    php artisan mustache:generate --compare roles controller        OK
    php artisan mustache:generate --compare roles request           OK
    php artisan mustache:generate --compare roles model             To do
    php artisan mustache:generate --compare roles index             OK
    php artisan mustache:generate --compare roles create            OK
    php artisan mustache:generate --compare roles edit              OK
    

configurations

    php artisan mustache:generate --compare configurations controller       OK

    php artisan mustache:generate --compare roles request                   OK
        missing support for regexpr
        missing support for short list
    
    php artisan mustache:generate --compare configurations edit             OK
    php artisan mustache:generate --compare configurations create           OK
    php artisan mustache:generate --compare configurations index            OK
    
users

    php artisan mustache:generate --compare users controller
    php artisan mustache:generate --compare users request
    php artisan mustache:generate --compare users model
    php artisan mustache:generate --compare users index                     OK
    php artisan mustache:generate --compare users create            password and password_confirmation missing
    php artisan mustache:generate --compare users edit

user_roles

    php artisan mustache:generate --compare user_roles controller       missing support for user_list and role_list
    php artisan mustache:generate --compare user_roles request              OK
    php artisan mustache:generate --compare user_roles model
    php artisan mustache:generate --compare user_roles index        user_name and role_name support missing
    php artisan mustache:generate --compare user_roles create               OK
    php artisan mustache:generate --compare user_roles edit                 OK

calendar_event

    php artisan mustache:generate --compare calendar_events controller     Support for dateFormat missing
    php artisan mustache:generate --compare calendar_events request        missing date_format and regexp
    php artisan mustache:generate --compare calendar_events model
    php artisan mustache:generate --compare calendar_events index
    php artisan mustache:generate --compare calendar_events create
    php artisan mustache:generate --compare calendar_events edit
    