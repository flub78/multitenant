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
