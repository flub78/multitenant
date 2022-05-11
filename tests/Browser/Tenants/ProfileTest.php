<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Profile CRUD
 *  
 * @author frederic
 *
 */
class ProfileTest extends DuskTestCase {


	public function setUp(): void {
		parent::setUp ();
		
		$database = "tenanttest";
		
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
			$this->login($browser);
			
			$browser->screenshot('Tenants/after_login');
		} );
	}
	
	public function test_profile_CRUD() {
		$this->browse ( function (Browser $browser) {
		
            // Goto the index page
			$browser->visit ( '/profile' )
			->assertSee ( __('profile.title') );
			
			// Take a screenshot
			$browser->screenshot('Tenants/profile');
			
			// See pagination elements
			$browser->assertSee ( 'Search' )
			->assertSee ( 'Previous' )
			->assertSee ( 'Next' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
			
			// Press the button to add an element
			$browser->press ( __('profile.add')  )
			->assertPathIs('/profile/create');
			
            // The rest is not correctly implemented yet
            return;
            			
			// Fill the form to create an element
			$browser
			->select ( 'key', 'app.timezone')
			->type ( 'value', 'Europe/Paris')
			->press ( 'Submit' )
			->assertPathIs('/profile')
			->assertSee ( 'Paris' )
			->assertSee ( "Showing 1 to 1 of 1 entries" );
			
			
			// delete
			$browser->press('Delete')
			->assertPathIs('/profile')
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
