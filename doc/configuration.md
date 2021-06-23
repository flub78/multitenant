# Configuration

The application uses the [Laravel configuration](https://laravel.com/docs/8.x/configuration) for global configuration (when all tenants are configured the same way).

    $value = config('app.timezone');
    
But it is quite obvious that configuration should be possible per tenant.

However basic configuration usually implies access to the configuration files on the file system. So implementing a file based configuration per tenant would imply to provide access to the file system and would require security mechanisms to insure that a tenant cannot access the configuration of another tenant.

It is possible to put this kind of solution in place (a special controller to edit configuration files, ftp access, etc.) but most of these solutions are likely more complicated than to store the configuration of each tenant in its own database.

Conclusion:  a Configuration controller and model, just a basic key value implementation.