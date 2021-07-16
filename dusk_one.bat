cp .env .env.svg
cp .env.dusk.tenants .env

php artisan dusk --env=.env.dusk.tenants --browse tests/Browser/Tenants/LocalizationAndConfigurationTest.php
@rem php artisan dusk --env=.env.dusk.tenants --browse tests/Browser/Tenants/CalendarTest.php

cp .env.svg .env