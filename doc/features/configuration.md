# Configuration per tenant

The application uses the [Laravel configuration](https://laravel.com/docs/8.x/configuration) for global configuration (when all tenants are configured the same way).

    $value = config('app.timezone');    // "UTC", "Europe/Paris"
    $value = config('app.locale');      // "fr", "en"


A helper has been designed to support tenant specific configuration. It is mainly a key value mechanism different for each tenant.

    use App\Helpers\Config;

    Config::config($key)       get a configuration value
     
    Config::set('app.timezone', $timezone);    set a configuration value
