<?php

/**
 * Test cases:
 *
 * Nominal:
 *
 * Error test case:
 * delete a non existing backup
 * restore a non existing backup
 *
 * attempt to create, restore or delete a backup using the artisan commands
 */
namespace tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\TenantHelper;
use App\Models\Tenant;

class CentralBackupArtisanTest extends TestCase {

	// Clean up the database
	// Not refreshing the database may break others tests
	use RefreshDatabase;

	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
	}

	function __destruct() {
		$this->user->delete ();
	}

	/**
	 * get a signature/hash of the database
	 * backup current database state (check that a new backup is created)
	 * change the database
	 * restore the previous state
	 * check that the database is back in its initial state
	 * delete the backup
	 * check that there is one less backup in the local storage
	 */
	public function test_backup_create_restore_delete() {
		$this->be ( $this->user );

		$initial_count = TenantHelper::backup_count ();
		
		// backup list
		$exitCode = Artisan::call('backup:list');
		$this->assertEquals($exitCode, 0, "No error on backup:list");
		
		/**
		// Create a tenant
		$tenant_count = Tenant::count();
		$this->assertEquals(0, $tenant_count, 'no tenants after refresh');
		
		$new_tenant = 'third';
		$new_domain = 'third.tenants.com';
		$validatedData = ['id' => $new_tenant, 'domain' => $new_domain];
		$tenant = Tenant::create($validatedData);
		// No needs to create the storage ...

		
		// Get a signature of the database
		$domain_count = $tenant->domains()->count();
		$this->assertEquals(0, $domain_count, 'no domains after tenant creation');		
		 * 
		 */
		
		// create a backup
		$exitCode = Artisan::call('backup:create', []);
		$this->assertEquals($exitCode, 0, "No error on backup:create");		
		$this->assertEquals ($initial_count + 1,  TenantHelper::backup_count (),  "a backup has been created" );

		/**
		// Change the database
		$tenant->domains()->create(['domain' => $new_domain]);
		$domain_count = $tenant->domains()->count();
		$this->assertEquals(1, $domain_count, 'one domain created');
		*/
		
		/*
		 * Restore central database
    	 */
		$id = $initial_count + 1;
		$exitCode = Artisan::call("backup:restore --pretend --force $id");
		$this->assertEquals($exitCode, 0, "No error on backup:restore");
		
		/**
		// Check that the database modification has been lost
		$tnt = Tenant::findOrFail ( $new_tenant );
		$domain_count = $tnt->domains()->count();
		$this->assertEquals(0, $domain_count, 'domain creation overwritten');
				
		// Delete the tenant
		$tnt->delete ();
		*/
		
		// Delete the backup
		$exitCode = Artisan::call("backup:delete --force $id");
		$this->assertEquals($exitCode, 0, "No error on backup:delete");
		
		$this->assertEquals ($initial_count,  TenantHelper::backup_count (),  "the backups have been deleted" );				
	}

	/**
	 * 
	 */
	public function test_delete_non_existing_backup() {
		$exitCode = Artisan::call("backup:delete --force 999999999");
		$this->assertEquals($exitCode, 1, "Error on backup:delete");		
	}
	
	/**
	 *
	 */
	public function test_restore_non_existing_backup() {
		$exitCode = Artisan::call("backup:restore --force 999999999");
		$this->assertEquals($exitCode, 1, "Error on backup:restore");
	}
}
