# Test data Generation

## Tests pre-requisites

Except for installation tests the test pre-requisites are

* There is an existing admin user for the central application
* There is a test tenant created in the central application
* there is an existing admin user for the test tenant application

These minimal requirements are setup by restoring a standard test database before test.
There is one test database for tenant and one for central.

This mechanism will also be used for performance and load test to avoid generation 
of a big database on the fly.

## Tests Conventions

Tests data can be randomly generated. But it may be more convenient to use pseudo random data.

## Test Data for Complex relations

It is easy to use random data for testing simple database tables, it can be done with a simple factory.

For schema with complex relationship it is more tricky, generating fake data for complex data model with a lot of relations and dependencies between them.

    https://medium.com/@palypster/cascading-laravel-factories-b1eb5a214161




