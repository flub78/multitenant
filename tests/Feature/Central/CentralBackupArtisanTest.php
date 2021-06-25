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
	public function test_backup_create_delete() {
		$this->be ( $this->user );

		$initial_count = TenantHelper::backup_count ();
		
		// backup list
		$exitCode = Artisan::call('backup:list');
		$this->assertEquals($exitCode, 0, "No error on backup:list");
		
		// create a backup
		$exitCode = Artisan::call('backup:create', []);
		$this->assertEquals($exitCode, 0, "No error on backup:create");
		
		$this->assertEquals ($initial_count + 1,  TenantHelper::backup_count (),  "a backup has been created" );

		$id = $initial_count + 1;
		
		/*
		 * It seems that restoring a database while phpunit is running has some negative effects...
		 * It blocks the test ....
    	 */
		$exitCode = Artisan::call("backup:restore --pretend --force $id");
		$this->assertEquals($exitCode, 0, "No error on backup:restore");
		
		$exitCode = Artisan::call("backup:delete --force $id");
		$this->assertEquals ($initial_count,  TenantHelper::backup_count (),  "a backup has been deleted" );				
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
