<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Tenant;
use App\Helpers\TenantHelper;
use App\Helpers\DirHelper;


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
		parent::tearDown();	
		if ($this->tenancy) {
			DirHelper::rrmdir($this->storage);
		}
	}
		
	public function initializeTenancy()
	{
		$tenant = Tenant::create();
		tenancy()->initialize($tenant);
		
		$tenant = tenant('id');
		$this->storage = storage_path();
		$this->backup_dirpath = TenantHelper::backup_dirpath($tenant);
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
