<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * CodeGenType CRUD
 *  
 * @author frederic
 *
 */
class CodeGenTypeTest extends DuskTestCase {


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
	
	public function ttest_code_gen_type_CRUD() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/code_gen_type' )
			->assertSee ( 'Tenant code_gen_type' );
			
			$browser->screenshot('Tenants/code_gen_type');
			
			$browser->assertSee ( 'Search' )
			->assertSee ( 'Previous' )
			->assertSee ( 'Next' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
			
			$browser->press ( 'Add CodeGenType' )
			->assertPathIs('/code_gen_type/create');
			
			// app.locale	fr
			$browser
			->select ( 'key', 'app.timezone')
			->type ( 'value', 'Europe/Paris')
			->press ( 'Submit' )
			->assertPathIs('/code_gen_type')
			->assertSee ( 'Paris' )
			->assertSee ( "Showing 1 to 1 of 1 entries" );
			
			// Back to English
			$browser->visit ( '/code_gen_type/app.timezone/edit' )
			->assertSee ( 'Edit code_gen_type' )
			->type ( 'value', 'Europe/London')
			->press ( 'Update' )
			->assertPathIs('/code_gen_type')
			->assertSee ( 'app.timezone updated' )
			->assertSee ( 'London' )
			->assertSee ( 'Showing 1 to 1 of 1 entries' );
			
			// delete
			$browser->press('Delete')
			->assertPathIs('/code_gen_type')
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
