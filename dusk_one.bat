cp .env .env.svg
cp .env.dusk.tenants .env
php artisan dusk --env=.env.dusk.tenants --browse tests/Browser/Tenants/LocalizationAndConfigurationTest.php
cp .env.svg .env