# Database backup and restore

Both central and tenant databases can be backed up and restored independently. Either with artisan commands or through the backup controller.

## Artisan commands

### For central database

    php artisan backup:create
    php artisan backup:list
    php artisan backup:delete 2
    php artisan backup:delete backup-2021-05-26_075112.gz
    php artisan backup:restore 3
    php artisan backup:restore backup-2021-05-26_075112.gz

### For tenants

    php artisan tenants:run --tenants=Abbeville backup:list
    php artisan tenants:run --tenants=Abbeville backup:create
    php artisan tenants:run backup:delete 4 --tenants=Abbeville
    
or better

    php artisan --tenants=Abbeville backup:create
    php artisan --tenants=Abbeville backup:list
    php artisan --tenants=Abbeville backup:delete 2
    php artisan --all backup:create 
     