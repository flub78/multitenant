<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;
use PHPUnit\Framework\TestCase;

/**
 * User CRUD
 *  
 * @author frederic
 *
 */
class UserTest extends DuskTestCase {

	function __construct() {
		parent::__construct();
		
		$this->email1 = "titi@gmail.com";
		$this->email2 = "titi@free.fr";
		$this->password = "password4titi";
	}
	
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

	public function test_user_can_register() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/register' )
			->assertSee ( 'Register' );
			
			return;
			
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
			->assertSee ( 'Configuration app.timezone updated' )
			->assertSee ( 'London' )
			->assertSee ( 'Showing 1 to 1 of 1 entries' );
			
			// delete
			$browser->press('Delete')
			->assertPathIs('/configuration')
			->assertSee ( 'Configuration app.timezone deleted' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}
	
	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {
		
		$this->browse ( function ($browser)  {
			$browser->visit ( '/login' )
			->type ( 'email', env('TEST_LOGIN') )
			->type ( 'password', env('TEST_PASSWORD') )
			->press ( 'Login' )
			->assertPathIs ( '/home' );
			
			$browser->screenshot('Tenants/after_login');
		} );
	}
	
	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		
		$this->browse ( function ($browser)  {
			$browser->visit ( '/home' )
			->click('@user_name')
			->click('@logout')
			->assertPathIs ( '/' )
			->assertSee ('Register');
			
			$browser->screenshot('Tenants/after_logout');
		} );
	}
	
}
