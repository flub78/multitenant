<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // use CreatesApplication;
    
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';
		
		//Load .env.testing environment
		$app->loadEnvironmentFrom('.env.testing');
		echo "\ntest database=" . ENV("DB_DATABASE") . "\n";
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		
		return $app;
	}

}
