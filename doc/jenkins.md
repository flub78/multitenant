# Continuous integration

## Server

    https://flub78.ddns.net:8443/

    
## Static Analysis

    https://flub78.ddns.net:8443/job/Multitenant_Static_Analysis/    

## Unit and feature tests

Run the unit and feature tests.

## Deployment tests

### Installation and Initial deployment

* Automated deployments
* Execution of the End to End tests on the freshly deployed environment

Several cases:

#### Full featured applicationservers 

Cases when the server offers
    * ssh access
    * PHP at the right version
    * composer

#### Limited application servers

They only offers PHP at the right version for Apache.

No way to run composer of call artisan routines.

In this case the vendor directories need to be uploaded. And migrations need to be triggered from the application itself.

Installation steps
* create the database for central and supported tenants
* upload the environment including vendor
* The application itself should have a controller entry to migrate the central database
* create the initial admin user for central


### Upgrade and Migrations tests

* A specific deployment which is upgraded from version to version
* Run the End to End tests
* Check that the existing data are preserved and stil available after upgrade
