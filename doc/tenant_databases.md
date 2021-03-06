# Tenants Databases

The database organization is a little bit tricky in tenant environment.

This page is only related to multi-database

In this project tenants are implemented with https://tenancyforlaravel.com/

## Deployment

* One central database with a tenants and domains table

* One database per tenant

During deployment the central database must be migrated. Then the migration is automatically applied during tenant creation.

## Upgrade

In case of upgrade central database and tenant database must be migrated.

Note that the central database should never be reset with existing tenants. It it is required, tenant should be deleted first and recreated after the migration. Normally it should not be necessary.

Upgrades of the central database should be rare. 

See [migrations](migrations.md)
    
    
## Testing

Listing of the tenants

### List of tenants on default connection

    php artisan tenants:list
    Listing all tenants.
    [Tenant] id: Abbeville @ abbeville.tenants.com; abbeville.gvv.org
    [Tenant] id: test @ test.tenants.com
    
### List of tenants on test connection
    
    test-tenants-list.bat

    
### to migrate the tenants

    php artisan tenants:migrate --database=mysql_test
    
and for some reasons the php artisan tenants:migrate-fresh command does not support a database parameter... Note that this option does not exist for tenants:list either ...

Note that when running tenant tests the tenant context must already exists, in particular they must exist inside the tenant table of the central application.

    php artisan tenants:migrate-fresh --tenants=xxx

then
    
    php artisan tenants:migrate --database=mysql_test

Conclusions:

* It is better to use a special database connection for tests
* central database should never be reset 
* for convenience the same tenant named "test" is used for all tenant testing


    