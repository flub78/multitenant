@rem Unit tests
@rem ==========
    @rem helpers
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/ExampleTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/OsTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/DateFormatHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/HtmlHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/BladeHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/TranslationHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/UsersModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/MustacheHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/MustacheTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/MetadataHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/CodeGeneratorTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/BitsOperationsTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/TestsConventionsTest.php


    @rem tenants
    @rem -------
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CarbonTest.php

    @rem Tenant models
    @rem -------------
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ExperimentationOnModelTest.php

@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ConfigurationModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CalendarEventModelTest.php

@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/RoleModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/UserRoleModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/SchemaModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ViewSchemaModelTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/MetadataModelTest.php
@rem php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/CodeGenTypeModelTest.php
@rem php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/CodeGenTypesView1ModelTest.php
@rem php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/UserRolesView1ModelTest.php
@rem php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/ProfileModelTest.php
php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/MotdModelTest.php


@rem Feature tests
@rem =============

@rem Tools tests
@rem -----------
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/DevStatsArtisanTest.php

@rem Central application tests
@rem -------------------------
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralFeatureExampleTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/LoginTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralTenantHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/BackupControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/TenantControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralHomeControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/AdminAccessTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralBackupArtisanTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Central/UserControllerTest.php


    @rem Helpers
    @rem -------
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/DirHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantHelperTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/BackupHelperTest.php

@rem Tenant application tests
@rem ------------------------
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantFeatureExampleTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantBackupArtisanTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/LocalizationTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ArtisanMustacheTest.php

    @rem Teanants Models
    @rem ---------------
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantModelTest.php

    @rem Controllers
    @rem -----------
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantBackupControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CalendarEventControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ConfigurationControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantHomeControllerTest.php
php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TestControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/UserControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/RoleControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/UserRoleControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/MetadataControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CodeGenTypeControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CodeGenTypesView1ControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ProfileControllerTest.php
php vendor/phpunit/phpunit/phpunit  tests/Feature/Tenants/MotdControllerTest.php

    @rem APIs
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CalendarEventControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Api/RoleControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CodeGenTypesView1ControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit tests/Feature/Api/ProfileControllerTest.php
@rem php vendor/phpunit/phpunit/phpunit  tests/Feature/Api/MotdControllerTest.php
