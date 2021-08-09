@rem php artisan dusk --colors=always --browse tests/Browser/Central/TenantsTest.php
php artisan dusk  --browse tests/Browser/Central/UserTest.php
@rem php artisan dusk --colors=always --browse tests/Browser/Central/BackupsTest.php

cp .env .env.svg
cp .env.dusk.tenants .env

@rem Only one test must be enabled at any time, because screenshots are deleted between tests

@rem php artisan dusk --browse --colors=always --env=.env.dusk.tenants tests/Browser/Tenants/LocalizationTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/CalendarTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/ConfigurationTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/UserTest.php

cp .env.svg .env