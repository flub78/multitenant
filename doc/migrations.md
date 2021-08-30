# Migrations

## Migrations for upgrade deployment or manual test environment

### Central application migration

    php artisan migrate

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
    