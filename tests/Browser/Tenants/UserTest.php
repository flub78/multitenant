<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use App\Helpers\BackupHelper;
use Tests\Browser\UserSupport;

/**
 * User CRUD
 *
 * @author frederic
 *        
 */
class UserTest extends UserSupport {

	function __construct() {
		parent::__construct ();
		
		$this->screenshots_dir = "Tenants";
	}
	
	public function setUp(): void {
		parent::setUp ();

		$database = "tenanttest";

		/**
		 * echo "\nENV=" .
		 * env ( 'APP_ENV' ) . "\n";
		 * echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		 * echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		 * echo "url=" . env('APP_URL') . "\n";
		 * echo "database=$database\n";
		 */

		// Restore a test database
		$filename = storage_path () . '/app/tests/tenant_nominal.gz';
		$this->assertFileExists ( $filename, "tenant_nominal test backup found" );
		BackupHelper::restore ( $filename, $database, false );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {
		$this->browse ( function ($browser) {
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			
			$browser->screenshot ( 'Tenants/after_login' );
		} );
	}

	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		$this->browse ( function ($browser) {
			$this->logout($browser);
			
			$browser->screenshot('Tenants/after_logout');
		} );
	}	
}
