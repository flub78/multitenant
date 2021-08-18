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

    php artisan tenants:migrate --database=mysql_test