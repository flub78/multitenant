# Test data Generation

## Tests pre-requisites

Except for installation tests the test pre-requisites are

* The central database exists and the schema is up to date
* There is an admin user in this database
* There is a tenant test in the database
* The tenant test database exists
* There is an admin in the tenant test database

The test environment should be prepared by a specific jenkins job. Which should also perform some minimal smoke tests (login, logout and access to one view).

These minimal requirements are setup by restoring standard test databases before the test.
There is one test database for tenant and one for central.

This mechanism will also be used for performance and load test to avoid generation 
of a big database on the fly.

## Tests Conventions

Tests data can be randomly generated. But it may be more convenient to use pseudo random data.

## Test Data for Complex relations

It is easy to use random data for testing simple database tables, it can be done with a simple factory.

For schema with complex relationship it is more tricky, generating fake data for complex data model with a lot of relations and dependencies between them.

    https://medium.com/@palypster/cascading-laravel-factories-b1eb5a214161
    
By default the code generator cascades the factories. For example when a user role is created by the factory, it also creates a user and a role which are referenced.

It works well for simple cases, you may consider to change the factory to reuse existing values or to pre fill the test database. Note that tests relying on pre filled databases are more complex to manage. You need to generate the database and to manage them under configuration to be sure to run the correct tests with their matching database. It may be the best option however when there are a lot of data to generate.





