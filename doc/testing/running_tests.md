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

	run\one.bat to execute one phpunit test
	run\tests.bat to run all of them
	run\coverage.bat

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

	run\dusk_one.bat  to run one dusk test
	run\dusk_central.bat to run the end to end tests on central application
	run\dusk_tenants.bat to run the end to end tests on tenant application

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


### Jenkins server on Oracle cloud

    https://jenkins2.flub78.net:8443/

    cd oracle_cloud/
    export PUBLIC_KEY="$HOME/.ssh/oracle.pub"
    export PRIVATE_KEY="$HOME/.ssh/oracle"
    source setenv.sh
    source jenkins2.setenv

    ssh -i $PRIVATE_KEY ubuntu@jenkins2.flub78.net
    sudo -i -u jenkins
    cd workspace/Multitenant_phpunit/
    php --version
    PHP 8.1.18 (cli) (built: Apr 14 2023 04:39:44) (NTS)

    php vendor/phpunit/phpunit/phpunit tests/Unit/ExampleTest.php
    PHPUnit 9.5.20 #StandWithUkraine
    .                                                                   1 / 1 (100%)
    Time: 00:00.007, Memory: 8.00 MB
    OK (1 test, 1 assertion)

    php vendor/phpunit/phpunit/phpunit tests/Unit/OsTest.php        OK

    php vendor/phpunit/phpunit/phpunit tests/Unit/UsersModelTest.php
    InvalidArgumentException: Please provide a valid cache path

    chmod +x valid_cache_path.sh
    ./valid_cache_path.sh

## End to End tests on deployment

By default these tests should be able to run on any deployment. The CD pipeline install a test environment on one public server and then run the tests. These tests have no direct access to the database nor any internal data. They are pure black box tests.

There are two kinds of deployment End to End tests
* After installation, the database is empty, no user is registered
* Tests on deployment, these test makes no assumption on the state of the database, they can run on any deployed environment and only rely on an existing admin user. On success, they delete all created data and create only very unlikely data that should not conflict with existing one. May be that prefixing all data names by "tests_on_deployment_" could avoid conflicts. 

# Tests workflow

## Static analysis

no pre-requisites except PHP 8.x

## phpunit

    Fetch the sources from git
    
### Definition of some environment variables

    export BASE_URL='http://tenants.com/'
    export APP_URL='http://tenants.com/'
    export TENANT_URL='http://test.tenants.com/'
    export INSTALLATION_PATH='/var/www/html/multi_phpunit'
    export SERVER_PATH='/var/www/html/tenants.com'
    export VERBOSE="-v"
    export TRANSLATE_API_KEY='...'


    export DB_HOST='localhost'
    export DB_USERNAME='root'
    export DB_PASSWORD='cpasbelo'
    export DB_DATABASE='multi_jenkins'

### Ansible script

3 roles:
    - env
    - directories
    - laravel
    
* Setup environment files from environment variables
* Setup a predifined user admin able to login
* Create some directories
* migrate and seed the database
* Generate test databases

Currently central_nominal.gz and tenants_nominal.gz are restored before the phpunit tests.

The test database contains test tenants, so is dependent on the domain used for testing. They must be created by the test suite once environment variables are set and not stored on github.

Another option is to replace the domain inside the backups with ansible.

    