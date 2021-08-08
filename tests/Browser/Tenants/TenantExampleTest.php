<?php

namespace Tests\Browser\Tenants;

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
class TenantExampleTest extends DuskTestCase {


	public function setUp(): void {
		parent::setUp ();
		
		$database = "tenanttest";
		
		/**
		echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		echo "url=" . env('APP_URL') . "\n";
		echo "database=$database\n";
		*/

		// Restore a test database
		$filename = storage_path () . '/app/tests/tenant_nominal.gz';
		$this->assertFileExists($filename, "tenant_nominal test backup found");
		BackupHelper::restore($filename, $database, false);		
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
			$browser->visit ( '/' )->assertSee ( 'Webapp - Welcome' );
		} );
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			
			$browser->screenshot('Tenants/after_login');
		} );
	}

}
