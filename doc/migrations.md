# Migrations

## Migrations for upgrade deployment or manual test environment

### Central application migration

    php artisan migrate
    
or

    php artisan migrate:fresh
    
When tenants already exist, if the migration is upward compatible, it may be convenient to backup the database, migrate it and restore the backup containing existing tenants. Note that it only works is the restore is compatible with existing backups (new fields have defaults).


### Tenant application migration

    php artisan tenants:migrate
    php artisan tenants:migrate --tenants=test --tenants=abbeville

or

    php artisan tenants:migrate-fresh
    
## Testing

### Central application
    
Migration of the central test database using the test connection
    
    php artisan migrate --database=mysql_test
    
### Tenant application
    
migrate the tenant database

Note that the connection does not matter.
    
    php artisan tenants:migrate-fresh
    php artisan tenants:migrate-fresh --tenants=test
    php artisan tenants:migrate-fresh --tenants=abbeville
    
    php artisan tenants:seed --tenants=abbeville --class="Database\Seeders\RoleSeeder"
    
Note that dusk tests restore a test database before execution. So when the test tenant database is migrated the backup
for testing must be regenerated.

1. migrate php artisan tenants:migrate-fresh --tenants=test
1. register the test user
1. cp storage\tenanttest\app\backup\backup-2021-12-29_205112.gz tests\data\tenant_nominal.gz
1. cp tests\data\tenant_nominal.gz storage\app\tests
    