# Database Relationships

As a framework this project must handle several kind of relationships.

    https://laravel.com/docs/8.x/eloquent-relationships

* One to one
* one to many
* one to many inverse (belongs to)
* Has one of many
* Has One Through
* Has many through
* Many to Many
* Polymorphic relationship

## No relationship, the CRUD

It is the case of a standalone table.

## One to one

Implement the hasOne relation. It is an alternative to have all the column in a single table.
By default the ELoquent interface also supports hasOneorNone relation.
By default the foreign key in the second table is *_id, user_id to reference user.id

In Eloquent navigation is done through hasOne in one way and belongsTo in the other way.

Examples: 
* A vehicle has one registration document.
* A user is one club member
* A member has one account for accounting 

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

* one to one: user, address
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
        
        