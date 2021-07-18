cp .env .env.svg
cp .env.dusk.tenants .env
php artisan dusk --env=.env.dusk.tenants --browse --colors=always --log-junit results/dusk_tenants.xml tests/Browser/Tenants
cp .env.svg .env