<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Motd CRUD
 *  
 * @author frederic
 *
 */
class MotdTest extends DuskTestCase {


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
	
	public function test_motd_CRUD() {
		$this->browse ( function (Browser $browser) {
		
            // Goto the index page
			$browser->visit ( '/motd' )
			->assertSee ( __('motd.title') );
			
			// Take a screenshot
			$browser->screenshot('Tenants/motd');
			
			// See pagination elements
			$browser->assertSee ( 'Search' )
			->assertSee ( 'Previous' )
			->assertSee ( 'Next' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
			
			// Press the button to add an element
			$browser->press ( __('motd.add')  )
			->assertPathIs('/motd/create');

            // The rest is not correctly implemented yet
            return;
            			
			// Fill the form to create an element
            $browser
            ->type ('first_name', 'John')       
            ->type ('last_name', 'Doe')
            ->type ('birthday', '08-29-1959' . "\n")
            ->select('user_id');
            
            // Validate and check the result
            $browser
            ->press ( 'Submit' )
            ->assertPathIs('/motd')
            ->assertSee ( "Showing 1 to 1 of 1 entries" );
            
            $browser->assertSee('John');
            
            // Create another element
            
            // Use it to update the initial element
            // Goto the index page
            $browser->visit ( '/motd/1/edit' )
            ->assertSee ( __('general.edit') )
            ->assertSee ( __('motd.elt') );
            
            $browser
            ->type ('first_name', 'Joe')
            ->type ('last_name', 'Dalton')
            ->type ('birthday', '08-29-1959' . "\n")
            ->select('user_id');
            $browser->screenshot('Tenants/motd_edit_form');
			
            // Check that the first element has been modified
            // Validate and check the result
            $browser
            ->press ( 'Update' )
            ->assertPathIs('/motd')
            ->assertSee('Dalton')
            ->assertSee ( "Showing 1 to 1 of 1 entries" );
            $browser->screenshot('Tenants/motd_after_update');

			// delete
            $browser
            ->visit ( '/motd' );
            $browser->screenshot('Tenants/motd_deleting');
            
			$browser->press('Delete')
			->assertPathIs('/motd')
			->assertSee ( 'deleted' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}
	
    /**
     * Check that incorrect values are rejected
     *
     * @return void
     */
    public function test_validation() {
        $this->browse ( function (Browser $browser) {
            
            $browser->visit ( '/motd/create' )
            ->assertSee ( __('motd.new'));

            // Validate without filling required fields
            
            $browser
            ->press ( 'Submit' )
            ->assertPathIs('/motd/create')
            ->assertSee ( "is required" );
            
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
