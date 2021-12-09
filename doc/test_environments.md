# Test environments

## On windows

### Interactive test environment

xampp is configured to serve the development directory.

I uses two dummy domains for testing

    http://tenants.com/             for central application    
    http://abbeville.tenants.com/   for tenant application
    
environment variables are defined in the .env file.

### phpunit tests

phpunit tests can be executed with:
* runone.bat to execute one phpunit test
* runtest.bat to run all of them

phpunit tests use the same domains but a specific database
phpunit tests use the mysql_test database connection

All tests cases use the .env.testing environment file, which defines a master database user with the capacity
of creating databases and users.

By default the central database for testing is
DB_DATABASE=multi_test

Tenant databases are named tenant . tenant('id')

        $tenant_id = "test";
        $domain = 'test.tenants.com';

The test tenant is created and deleted before every test tenants. It implies that the central database must be up and running before to execute phpunit tenant tests.

### Dusk tests

They can be executed with

* dusk_one.bat  to run one dusk test
* dusk_central.bat to run the end to end tests on central application
* dusk_tenants.bat to run the end to end tests on tenant application

Dusk tests use the environment define in .env.dusk.tenants

Dusk tests uses the mysql_test database connection

### Dusk tests databases

As the refreshDatabase trait cannot be used in multitenant context and it can be convenient to start tests in a well defined contexts several databases are defined to initialize the tests. By default they are stored in storage/app/tests.

* central_nominal.gz is used for tests of the central application
* tenant_nominal.gz is used for testing tenants.

Both of them contains at least a default user to login.



## phpunit tests on Jenkins on a Linux server

As all unit and feature tests they use the mysql_test connection.

config/database.php

            'database' => env('DB_DATABASE', 'multi'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),

default central database = multi_jenkins

default test tenant database = tenanttest

Note that the TenantsController test try to create and delete two tenants, named autotest and autotest2.

When tests are running on the jenkins server itself, I am not really keen on giving the jenkins user root privileges. The clean way of doing it would implies deploying containers or VM on which jenkins has full control.
For the moment, the Apache server is statically configured with two virtual hosts on tenants.com and test.tenants.com or any other domain and subdomain. 

Note that the tests should depend on some environment variable to define

CENTRAL_APPLICATION_DOMAIN
TEST_TENANT_APPLICATION_DOMAIN


## End to End tests on deployment

By default these tests should be able to run on any deployment. The CD pipeline install a test environment on one public server and then run the tests.