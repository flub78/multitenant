@rem Dusk tests for Central
@rem ======================
@rem php artisan dusk --colors=always --browse tests/Browser/MinimalDuskTest.php
@rem php artisan dusk --colors=always --browse tests/Browser/Central/WelcomeTest.php
@rem php artisan dusk --colors=always --browse tests/Browser/Central/TenantsTest.php
@rem php artisan dusk  --browse tests/Browser/Central/UserTest.php
@rem php artisan dusk --colors=always --browse tests/Browser/Central/BackupsTest.php
@rem ### php artisan dusk --colors=always --browse tests/Browser/Central/PasswordResetTest.php

@rem Production tests do not care about environment, only an URL and admin user
@rem php artisan dusk --browse --colors=always 


@rem Dusk tests for Tenants
@rem ======================
cp .env .env.svg
cp .env.dusk.tenants .env

@rem Only one test must be enabled at any time, because screenshots are deleted between tests

php artisan dusk --browse --colors=always --env=.env.dusk.tenants tests/Browser/Tenants/LocalizationTest.php
@rem php artisan dusk --browse  --colors=always --env=.env.dusk.tenants  tests/Browser/Tenants/CalendarTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/ConfigurationTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/UserTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/BackupsTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/ProfileTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/CodeGenTypeTest.php
cp .env.svg .env


@rem Dusk tests for Deployed Applications 
@rem =====================================

@rem These tests are absolute white box tests, they only relies on the URL and domain where the applciation is deployed
@rem cp .env .env.svg
@rem cp .env.dusk.deployed_tenant .env

@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Deployment/tenants/production/TenantProductionExampleTest.php
@rem php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Deployment/tenants/production
@rem cp .env.svg .env
