<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;


/**
 * @author frederic
 * 
 * TODO Tenant creation should survive if storage exists
 * TODO Tenant creation should survive if database exists
 * TODO Tenant update does not update the domain
 *
 */
class TenantsTest extends DuskTestCase {

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
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser);
						
			$browser->assertSee ( 'Users' )
			->assertSee ( 'Tenants' )
			->assertSee ( 'Backups' )
			->assertSee ( 'Dashboard' );
		} );
	}

	public function test_tenants() {
		
		$this->browse ( function ($browser)  {
			$tenant_id = "big_tenant";
			$domain = 'big.tenant.fr';
			$new_domain = 'big.tenant.com';
			
			// Read
			$browser->visit('/tenants')
			->assertPathIs('/tenants')
			->assertSee ( 'Edit' )
			->assertSee ( 'Delete' )
			->assertSee ( 'Domain');
			
			$initial_count = $this->datatable_count($browser, "count");
			
			// Create
			$browser->press ( 'New tenant' )
			->assertPathIs('/tenants/create');
			
			$browser->type ( 'id', $tenant_id)
			->type ( 'domain', $domain)
			->press ( 'Add tenant' )
			->assertPathIs('/tenants')
			->assertSee ($tenant_id)
			->assertSee ($domain);
			
			$new_count = $this->datatable_count($browser, "count");
			$this->assertEquals($initial_count + 1, $new_count);
			
			// Update
			$path = '/tenants/' . $tenant_id . '/edit';
			
			$browser->click('@edit_' . $tenant_id)
			->assertPathIs($path)
			->assertSee ('Edit Tenant')
			->assertSee ('Name')
			->assertSee ('Domain')
			->type ( 'domain', $new_domain)
			->press ( 'Edit Tenant' )
			->assertPathIs('/tenants');
			
			$new_count = $this->datatable_count($browser, "count");
			$this->assertEquals($initial_count + 1, $new_count, 'no count change on update');
			
			// Delete
			$browser->click('@delete_' . $tenant_id)
			->assertPathIs('/tenants')
			->assertSee ('Name')
			->assertSee ('Domain')
			->assertSee('deleted');
			
			$browser->visit('/tenants')
			->assertDontSee($tenant_id);

			$final_count = $this->datatable_count($browser, "count");
			$this->assertEquals($initial_count, $final_count, 'back to initial count');
			
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
			
			$browser->screenshot('Central/after_logout');
		} );
	}
	
}
