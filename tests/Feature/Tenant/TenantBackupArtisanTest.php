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
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\TenantHelper;
use App\Helpers\DirHelper;

class TenantBackupArtisanTest extends TenantTestCase {

	// Clean up the database
	// Not refreshing the database may break others tests
	// use RefreshDatabase; (not usable in Tenant context)

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
	
	public function setUp(): void
	{
		parent::setUp();
		
		$tenant = tenant('id');
		
		// TODO create test tenant local storage
		echo "\nsetup for tenant $tenant\n";
		// TODO $this->storage = TenantHelper::storage_dirpath($tenant);
		$this->storage = storage_path();
		$this->backup_dirpath = TenantHelper::backup_dirpath($tenant);
		echo "tenant backup dirpath $this->backup_dirpath\n";
		if (!is_dir($this->backup_dirpath)) {
			mkdir($this->backup_dirpath, 0777, true);
		}
	}
	
	public function tearDown(): void {
		parent::tearDown();
		
		// cleanup test tenant local storage
		DirHelper::rrmdir($this->storage);
	}
	
	public function test_setup() {
		$this->assertTrue(is_dir($this->backup_dirpath));	
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
	public function ttest_backup_create_delete() {
		$this->be ( $this->user );
		
		$tenant = tenant('id');

		$initial_count = TenantHelper::backup_count ($tenant);
				
		// backup list
		$exitCode = Artisan::call('backup:list', ['--tenant' => $tenant]);
		$this->assertEquals($exitCode, 0, "No error on backup:list");
		
		// create a backup
		$exitCode = Artisan::call("backup:create", ['--tenant' => $tenant]);
		$this->assertEquals($exitCode, 0, "No error on backup:create");
		
		$this->assertEquals ($initial_count + 1,  TenantHelper::backup_count ($tenant),  "a backup has been created" );

		$id = $initial_count + 1;
		
		/*
		 * It seems that restoring a database while phpunit is running has some negative effects...
		 * It blocks the test ....
		 */
		
		$exitCode = Artisan::call("backup:delete --force --tenant=$tenant $id");
		$this->assertEquals ($initial_count,  TenantHelper::backup_count (),  "a backup has been deleted" );				
	}

	
	/**
	 * 
	 */
	public function ttest_delete_non_existing_backup() {
		$tenant = tenant('id');
		$exitCode = Artisan::call("backup:delete --force --tenant=$tenant 999999999");
		$this->assertEquals($exitCode, 1, "Error on backup:delete");		
	}
	
	/**
	 *
	 */
	public function ttest_restore_non_existing_backup() {
		$tenant = tenant('id');
		$exitCode = Artisan::call("backup:restore --force --tenant=$tenant 999999999");
		$this->assertEquals($exitCode, 1, "Error on backup:restore");
	}
}
