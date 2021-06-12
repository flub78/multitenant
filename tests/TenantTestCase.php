<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Tenant;

abstract class TenantTestCase extends BaseTestCase
{
	
	protected $tenancy = false;

	public function setUp(): void
	{
		parent::setUp();
		
		if ($this->tenancy) {
			$this->initializeTenancy();
		}
	}
	
	public function initializeTenancy()
	{
		$tenant = Tenant::create();
		tenancy()->initialize($tenant);
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
