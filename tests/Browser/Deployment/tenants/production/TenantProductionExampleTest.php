<?php

namespace Tests\Browser\Deployment\Tenants\Production;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Dusk Tenant Test Example
 * 
 * As end to end tests are white box tests, we should not rely on any context,
 * only the url and a pre-existing database state. So there is no needs
 * to generate a tenant context like for unit or feature tests.
 * 
 * @author frederic
 *
 */
class TenantProductionExampleTest extends DuskTestCase {


	public function setUp(): void {
		parent::setUp ();
		
		$database = "tenanttest";
		
		echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		echo "url=" . env('APP_URL') . "\n";
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function testBasicExample() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/' )->assertSee ( 'Webapp - Welcome' );     // Application wecome page
		} );
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser);
			
			$browser->screenshot('Tenants/after_login');
		} );
	}

}
