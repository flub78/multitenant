<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;


class WelcomeTest extends DuskTestCase {

	public function setUp(): void {
		parent::setUp ();
		/**
		echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		echo "DB_DATABASE=" . env ( 'DB_DATABASE' ) . "\n";
		echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		*/
	
		// Restore a test database
		$filename = TenantHelper::storage_dirpath() . '/app/tests/central_nominal.gz';
		$this->assertFileExists($filename, "central_nominal test backup found");
		$database = env ( 'DB_DATABASE' );
		BackupHelper::restore($filename, $database, false);
		
		// Todo accessing the database does not work for end to end tests
		/*
		$count = User::count ();
		$this->assertEquals ( 3, $count );
		*/
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function testWelcome() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/' )
			->assertSee ( 'Laravel' )
			->assertSee ( 'Register' );
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
			
			$browser->screenshot('Central/after_login');
			
			$browser->assertSee ( 'Users' )
			->assertSee ( 'Tenants' )
			->assertSee ( 'Backups' )
			->assertSee ( 'Dashboard' );
		} );
	}

	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		$this->browse ( function ($browser) {
			$browser->visit ( '/home' )
			->click ( '@user_name' )
			->click ( '@logout' )
			->assertPathIs ( '/' )
			->assertSee ('Register');
			
			$browser->screenshot('Central/after_logout');
		} );
	}
	
}
