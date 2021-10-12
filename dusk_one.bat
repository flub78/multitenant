@rem php artisan dusk --colors=always --browse tests/Browser/Central/TenantsTest.php
@rem php artisan dusk  --browse tests/Browser/Central/UserTest.php
@rem php artisan dusk --colors=always --browse tests/Browser/Central/BackupsTest.php
@rem ### php artisan dusk --colors=always --browse tests/Browser/Central/PasswordResetTest.php

cp .env .env.svg
cp .env.dusk.tenants .env

@rem Only one test must be enabled at any time, because screenshots are deleted between tests

@rem php artisan dusk --browse --colors=always --env=.env.dusk.tenants tests/Browser/Tenants/LocalizationTest.php
php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/CalendarTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/ConfigurationTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/UserTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/BackupsTest.php

cp .env.svg .env