@rem Unit tests
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/ExampleTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/OsTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/DateFormatHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/HtmlHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/UsersModelTest.php
php vendor/phpunit/phpunit/phpunit tests/Unit/SchemaModelTest.php

@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ConfigurationModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CalendarEventModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CarbonTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/RoleModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/UserRoleModelTest.php

@rem Central application tests
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralFeatureExampleTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/LoginTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralTenantHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/BackupControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/TenantControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralHomeControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/AdminAccessTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralBackupArtisanTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/UserControllerTest.php


@rem Tenant application tests
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantFeatureExampleTest.php
@rem >>>>> php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantBackupArtisanTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/LocalizationTest.php

    @rem Helpers
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/DirHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/BackupHelperTest.php

    @rem Models
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantModelTest.php

    @rem Controllers
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantBackupControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CalendarEventControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ConfigurationControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantHomeControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TestControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/UserControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/RoleControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/UserRoleControllerTest.php

    @rem APIs
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CalendarEventControllerTest.php

