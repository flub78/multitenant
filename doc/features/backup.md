# Database backup and restore

Both central and tenant databases can be backed up and restored independently. Either with artisan commands or through the backup controller.

## PHP artisan commands

### For central database

    php artisan backup:create
    php artisan backup:list
    php artisan backup:delete 2
    php artisan backup:delete backup-2021-05-26_075112.gz
    php artisan backup:restore 3
    php artisan backup:restore backup-2021-05-26_075112.gz

### For tenants

    php artisan --tenant=Abbeville backup:create
    php artisan --tenant=Abbeville backup:list
    php artisan --tenant=Abbeville backup:delete 2
    php artisan --tenant=Abbeville backup:restore 2
    php artisan --all backup:create 
     