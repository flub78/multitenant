# Multi-tenancy

This project is a framework for multi-tenant WEB application.

By default it uses the multi-tenant multi-database model

There is one central application to manage tenants and one tenant application per tenant.

Each tenant uses his own database and it is possible to backup or restore a tenant database independently.

Tenant application are selected by sub domains.

## Implementation

The application uses the tenancy for Laravel module (see below).

## References

* [Tenancy for Laravel](https://tenancyforlaravel.com/)

* [Stancl module documentation](https://github.com/stancl/tenancy-docs)

* [Multi-tenancy package comparison](https://tenancyforlaravel.com/docs/v3/package-comparison/)

## Modifications

By default the tenancy mechanism has a few characteristics:

* The tenant database is created when a tenant is created
* The tenant storage is created when a tenant is created.

It makes the upgrade of the central database complicated as there is no way if you want to keep existing tenants.

### Investigations

1. Let's try to create a tenant using a pre-existing database.
1. Some things can be changed by extending classes (e.g. the Tenant model),

1. Let's use TenantFeatureExampleTest.php to experiment about tenants:

1. A tenant implements a contract (an interface)
    \vendor\stancl\tenancy\src\Contracts\Tenant.php
        a key name
        
A tenant has an attribute tenancy_db_name, but also an internal named db_name

multitenant\vendor\stancl\tenancy\src\DatabaseConfig.php

1) tests\Feature\Central\TenantControllerTest::test_tenant_edit_view_existing_element
Stancl\Tenancy\Exceptions\TenantDatabaseAlreadyExistsException: Tenant cannot be created. Reason: Database tenantautotest already exists.

C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\vendor\stancl\tenancy\src\Database\DatabaseManager.php:85
C


