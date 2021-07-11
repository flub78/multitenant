cp .env .env.svg
cp .env.dusk.tenants .env
php artisan dusk --env=.env.dusk.tenants tests/Browser/Tenants
cp .env.svg .env