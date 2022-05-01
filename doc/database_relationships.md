# Database Relationships

As a framework this project must handle several kind of relationships.

    https://laravel.com/docs/8.x/eloquent-relationships

* One to one
* one to many
* one to many inverse (belongs to)
* Has one of many
* Has One Through
* Has many through
* Many to Many          (Ex : users - roles)
* Polymorphic relationship

Eloquent relationships are defined as methods on your Eloquent model classes. 

## No relationship, the CRUD

It is the case of a standalone table.

## One to one

Implement the hasOne relation. It is an alternative to have all the column in a single table.
By default the ELoquent interface also supports hasOneorNone relation. (I cannot find that in the documentation...)
By default the foreign key in the second table is *_id, user_id to reference user.id

In Eloquent navigation is done through hasOne in one way and belongsTo in the other way.

Examples: 
* A vehicle has one registration document.
* A user is one club member
* A member has one account for accounting 
* a calendar event has one or none repetition

## one to many

Implement the hasMany relation.
In Eloquent it is possible to get a collection of the relationship

Examples;
* A club members hasMany(Address::class);


## one to many inverse (belongsTo)

Same that the inverse for one to one relationship

## Has one of many

Some helpers to find a specific element of a one two many relationship

## Has One Through

Easy way to navigate indirect one to one relationship in Eloquent

## Has many through

Same

## Many to Many

Example:
* tables: users, roles, and role_user. role_user contains user_id and role_id.

In the example above the relationship is unique (a user has a role or not, her cannot have the same role several times). This restriction can be implemented by declaring an index with the two foreign keys. As one of the design principle is to be data driven it is better to enforce the limitation in the database that at any other level (controller or model).

Note that even if I like Laravel quite a lot, The Ruby on Rail to implement data checks in the model is a better approach than the Laravel one to put these rules in a request, even if nothing in Laravel prevent the RoR approach. It is a better approach because the data coherency checks are done whatever the way the data are modified and if data can be changed through different workflow in different controllers the Request controls have to be duplicated.

I currently have in mind no real good example of multiple many to many relationship. We can imagine a rating system in which a user has several points in one category or another. It is a poor example as there are alternative implementations which do not require one database entry for each point...


## Polymorphic relationship

A child model belongs to more than one type of model. For example a comment could belong to the Post and Video models.

An attached document could belongs to several others models.

posts
    id - integer
    name - string

users
    id - integer
    name - string

images
    id - integer
    url - string
    imageable_id - integer
    imageable_type - string
    
It could be used for example if a billing line is related to a flight, and flight of different aircraft types are managed in separate tables. In Object Oriented Programming the issue would be approached through inheritance or composition.


## Example of database schema

* one to one: user, profile
* one to many: tenants, domains
* Many to many: users, roles, role_user

* Schedule, users, classrooms, instructors, sessions, students

    users
        id
        name
        email
        
    classrooms
        id
        description
        
    instructors
        id
        name
        email
        qualifications
        user_id
        
    students
        id
        name
        email
        user_id

    session:
        id
        subject
        comment
        date
        start_time
        end_time
        classroom_id
        instructor_id
        student_capacity
        
    student_session
        id
        session_id
        student_id
        
## Forign Key Constraints

Laravel also provides support for creating foreign key constraints, which are used to force referential integrity at the database level. For example, let's define a user_id column on the posts table that references the id column on a users table:

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    Schema::table('posts', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id');

        $table->foreign('user_id')->references('id')->on('users');
    });
    
    $table->foreignId('user_id')
      ->constrained()
      ->onUpdate('cascade')
      ->onDelete('cascade');
     
possible values are:
* "cascade"
* "restrict"
* "set null"

## Examples

As the goal of this project is to be a starting point for others projects in which there are example of the most frequent use cases, it is useful to have example of the most frequent relationships.

### No relationship

"configurations" is a model with no relationship. It is the most simple possible CRUD.

### One to one

users   profiles

Users may have profiles with their Name and first name and birthday.

### one to many (has one)

The calendar event repetition is a good example of a one to many relationship. When creating an event it is possible to define a repetition. Note that in this case it create multiple events all attached to the same repetition.

Note that this case may be tricky. It is not possible to generate all derived events of an event with a repetition and no repetition end date. ...

There are several approaches to handle this point.
* dynamic generation, only a few repetitions are generated, the others will be generated on the fly. (complex)
* hard limit on the number or repetitions (no more than 10 000) 
* hard end date limit, no end date means 31 December 2100. (simplest one, acceptable only if the end date is far enough, remember the years 2000 bug ...).

### one to many inverse (belongs to)

An events belongs to a repetition

### Has one of many

### Has One Through
### Has many through
### Many to Many          

    (Ex : users - roles)
    
### Polymorphic relationship

Attachments may be a good example. Attachments may be attach to any columns of any table and an item may have many of them.

