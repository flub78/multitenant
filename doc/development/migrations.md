# Migrations

## Migrations for upgrade deployment or manual test environment

** Warning: the migration filename must be a snake-case version of the class name.**

    Error migrations: Cannot declare class X, because the name is already in use

### Central application migration

    php artisan migrate
    
or

    php artisan migrate:fresh
    
When tenants already exist, if the migration is upward compatible, it may be convenient to backup the database, migrate it and restore the backup containing existing tenants. Note that it only works is the restore is compatible with existing backups (new fields have defaults).


### Tenant application migration

    php artisan tenants:migrate
    php artisan tenants:migrate --tenants=test --tenants=abbeville

or

    php artisan tenants:migrate-fresh --tenants=abbeville
    
to rollback

    php artisan tenants:rollback --tenants=abbeville --step=1
    php artisan tenants:rollback --tenants=test --step=1
    
## Testing

### Central application
    
Migration of the central test database using the test connection
    
    php artisan migrate --database=mysql_test
    
When the schema of the central application changes, it is important to regenerate the test database:

    # Register the test user interactively
    
    # Then save the central database to be used for testing
    php artisan backup:create
    backup C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\storage/app/backup\backup-2022-04-01_120903.gz created

    php artisan backup:test_install
    Local backups:central database
    copy .../app/backup/backup-2022-04-01_120903.gz tests/data/central_nominal.gz
    copy .../app/backup/backup-2022-04-01_120903.gz storage/app/tests/central_nominal.gz
    
### Tenant application
    
migrate the tenant database

Note that the connection does not matter.
    
    php artisan tenants:migrate-fresh
    php artisan tenants:migrate-fresh --tenants=test
    php artisan tenants:migrate-fresh --tenants=abbeville
    
    php artisan tenants:seed --tenants=abbeville --class="Database\Seeders\RoleSeeder"
    php artisan tenants:seed --tenants=test --class="Database\Seeders\RoleSeeder"
    
Note that dusk tests restore a test database before execution. So when the test tenant database is migrated the database for testing must be regenerated. 

    # migrate
    php artisan tenants:migrate-fresh --tenants=test
    # register the test user using the WEB application
    
    php artisan --tenant=test backup:create
    php artisan --tenant=test backup:test_install

    
