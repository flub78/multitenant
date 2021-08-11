<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;


/**
 * Feature: Displaying a Welcome screen
 * 		As a guest
 * 		I want to get to the welcome page
 * 		So I can access to the application features
 * 
 * 		rules: the welcome page is only accessible to guests on /
 * 		rules: I can register and login from there
 * 
 * @author frederic
 *
 */
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
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	/**
	 * Scenario: Visite welcome page
	 * 		Given I am not logged in
	 * 		When I visit the root page
	 * 		Then I see the welcome page
	 * 		And I can register
	 *
	 * @return void
	 */
	public function testWelcome() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/' )
			->assertSee ( 'Laravel' )
			->assertSee ( 'Register' );
			
			$browser->screenshot('Central/welcome');
		} );
		
	}

	/**
	 * Scenario: Login
	 * 		Given I am not logged in
	 * 		When I log in as a registered user
	 * 		Then I see the home page
	 * 		And I can access to central features
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser);

			$browser->screenshot('Central/after_login');
			
			$browser->assertSee ( 'Users' )
			->assertSee ( 'Tenants' )
			->assertSee ( 'Backups' )
			->assertSee ( 'Info' )
			->assertSee ( 'Dashboard' );
		} );
	}

	/**
	 * Scenario: Logout
	 * 		Given I am logged in
	 * 		When I log out
	 * 		Then I go back to the welcome page
	 *
	 * @return void
	 */
	public function test_logout() {
		$this->browse ( function ($browser) {
			
			$this->logout($browser);
			
			$browser->screenshot('Central/after_logout');
		} );
	}
	
}
