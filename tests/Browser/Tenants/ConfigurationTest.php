<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Configuration CRUD
 *  
 * @author frederic
 *
 */
class ConfigurationTest extends DuskTestCase {


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
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			
			$browser->screenshot('Tenants/after_login');
		} );
	}
	
	public function test_configuration_CRUD() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/configuration' )
			->assertSee ( 'Tenant Configuration' );
			
			$browser->screenshot('Tenants/configuration');
			
			$browser->assertSee ( 'Search' )
			->assertSee ( 'Previous' )
			->assertSee ( 'Next' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
			
			$browser->press ( 'Add Configuration' )
			->assertPathIs('/configuration/create');
			
			// app.locale	fr
			$browser->type ( 'key', 'app.timezone')
			->type ( 'value', 'Europe/Paris')
			->press ( 'Submit' )
			->assertPathIs('/configuration')
			->assertSee ( 'Paris' )
			->assertSee ( "Showing 1 to 1 of 1 entries" );
			
			// Back to English
			$browser->visit ( '/configuration/app.timezone/edit' )
			->assertSee ( 'Edit configuration' )
			->type ( 'value', 'Europe/London')
			->press ( 'Update' )
			->assertPathIs('/configuration')
			->assertSee ( 'app.timezone updated' )
			->assertSee ( 'London' )
			->assertSee ( 'Showing 1 to 1 of 1 entries' );
			
			// delete
			$browser->press('Delete')
			->assertPathIs('/configuration')
			->assertSee ( 'app.timezone deleted' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}
	
	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		
		$this->browse ( function ($browser)  {
			$this->logout($browser);
			
			$browser->screenshot('Tenants/after_logout');
		} );
	}
	
}
