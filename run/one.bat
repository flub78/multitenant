:: Unit tests
:: ==========
    
    :: helpers

:: php vendor/phpunit/phpunit/phpunit tests/Unit/OsTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/DateFormatHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/HtmlHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/BladeHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/TranslationHelperTest.php
php vendor/phpunit/phpunit/phpunit tests/Unit/UsersModelTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/MustacheHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/MustacheTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/MetadataHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/CodeGeneratorTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/BitsOperationsTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/TestsConventionsTest.php


    :: tenants
    :: -------
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CarbonTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/TenantTestCaseTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/PersonalAccessTokenModelTest.php
   
    :: Tenant models
    :: -------------
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ExperimentationOnModelTest.php

:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ConfigurationModelTest.php
:: :: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CalendarEventModelTest.php

:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/RoleModelTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/UserRoleModelTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/SchemaModelTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/ViewSchemaModelTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/MetadataModelTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Unit/Tenants/CodeGenTypeModelTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/CodeGenTypesView1ModelTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/UserRolesView1ModelTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/ProfileModelTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/MotdModelTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/MotdTodayModelTest.php

:: Feature tests
:: =============

:: Tools tests
:: -----------
:: php vendor/phpunit/phpunit/phpunit tests/Feature/DevStatsArtisanTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/DevReviewArtisanTest.php

:: Central application tests
:: -------------------------
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralFeatureExampleTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/LoginTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralTenantHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/BackupControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/TenantControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralHomeControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/AdminAccessTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/CentralBackupArtisanTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/UserControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Central/TestControllerTest.php


    :: Helpers
    :: -------
:: php vendor/phpunit/phpunit/phpunit tests/Feature/DirHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/LeitnerHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantHelperTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/BackupHelperTest.php

:: Tenant application tests
:: ------------------------
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantFeatureExampleTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantBackupArtisanTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/LocalizationTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ArtisanMustacheTest.php

    :: Teanants Models
    :: ---------------
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantModelTest.php

    :: Controllers
    :: -----------
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantBackupControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CalendarEventControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ConfigurationControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/TenantHomeControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/UserControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/RoleControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/UserRoleControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/MetadataControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CodeGenTypeControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CodeGenTypeControllerFilterTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/CodeGenTypesView1ControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ProfileControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/PersonalAccessTokenControllerTest.php

:: php vendor/phpunit/phpunit/phpunit  tests/Feature/Tenants/MotdControllerTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Feature/Tenants/MotdTodayControllerTest.php

    :: APIs
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CalendarEventControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Api/RoleControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CodeGenTypeControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CodeGenTypeControllerFilterTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CodeGenTypesView1ControllerTest.php
:: php vendor/phpunit/phpunit/phpunit tests/Feature/Api/ProfileControllerTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Feature/Api/MotdControllerTest.php
:: php vendor/phpunit/phpunit/phpunit  tests/Feature/Api/MotdTodayControllerTest.php
