# Localization

The application uses the [Laravel Localization mechanism](https://laravel.com/docs/8.x/localization).

Initially "en" and "fr" will be provided, more to validate the implementation than to provide a real feature. Some of the parent project had support in Dutch but there is currently no immediate requirement except for French. 

Note that it is quite obvious that local should be configurable per tenant, [see configuration](configuration.md)

## Html lang

Before starting:

    <html lang="en">
    
in former projects:

    <html lang="{{ app()->getLocale() }}">
    
By default the language is stored in config/app.php. Locale must be defined early in the request processing and possibly in one place (To to compute it in every controller to pass to the views and forget some places.

It cannot be done in the boot function of the AppServiceProvider as the tenant contexts are not set yet.

### Solution

A special tenancy bootstrapper class (LocaleTenancyBootstrapper) is used to

* Save the value of the current locale before the tenant context is established
* Switch it to the value specified in the tenant database configurations table at bootstrap
* Restore it when the tenant context is terminated

This class needs to be registered in the tenancy config file.

    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        App\LocaleTenancyBootstrapper::class,

With this approach, the locale can be set and changed in the tenant configuration (saved in the tenant database) and accessed the standard Laravel way with getLocale().


