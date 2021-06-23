<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Tenant;
use App\Helpers\TenantHelper;
use App\Helpers\DirHelper;
use Illuminate\Support\Facades\DB;

abstract class TenantTestCase extends BaseTestCase
{
	
	protected $tenancy = true;
	
	public function setUp(): void
	{
		parent::setUp();
		
		if ($this->tenancy) {		
			$this->initializeTenancy();
		}
	}
	
	public function tearDown(): void {
		
		if ($this->tenancy) {
			// delete the test tenant storage
			DirHelper::rrmdir($this->storage);

			// delete the test tenant database		
			$db_name = "tenant" . $this->tenant_id;;
			$sql = "DROP DATABASE IF EXISTS `$db_name`;";
			// DB::statement($sql);	
			
			DirHelper::rrmdir($this->storage);
		}
		parent::tearDown();
	}
	
	public function initializeTenancy()
	{
		$tenant_id = "test";
		$domain = 'test.tenants.com';
		
		// Cleanup tenant from previous execution.
		// tenant and storage database must not exixt when the test tenant is recreated
		
		// Reset the domain and tenant database
		DB::statement("SET foreign_key_checks=0");
		DB::statement("DELETE FROM domains;");
		Tenant::truncate();
		DB::statement("SET foreign_key_checks=1");
		
		// delete the testtenant database
		$db_name = "tenant" . $tenant_id;
		$sql = "DROP DATABASE IF EXISTS `$db_name`;";
		DB::statement($sql);
		
		// Create a fresh tenant context
		$tenant = Tenant::create(['id' => 'test', 'domain' => $domain]);
		$tenant->domains()->create(['domain' => $domain]);
		
		tenancy()->initialize($tenant);
		
		$tenant_id = tenant('id');
		$this->tenant_id = $tenant_id;
		$this->storage = storage_path();  // storage_path is only valid once the tenant created
		$this->backup_dirpath = TenantHelper::backup_dirpath($tenant_id);
		if (!is_dir($this->backup_dirpath)) {
			mkdir($this->backup_dirpath, 0777, true);
		}
	}
	
	
	// use CreatesApplication;
	
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';
		
		//Load .env.testing environment
		$app->loadEnvironmentFrom('.env.testing');
		
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		
		return $app;
	}
	
	
}
