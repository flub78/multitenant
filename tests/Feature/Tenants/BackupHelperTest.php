<?php

namespace Tests\Feature;

use Tests\TenantTestCase;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;
use App\Models\User;


/**
 * Test BackupHelper in a tenant context
 * @author frederic
 *
 */
class BackupHelperTest extends TenantTestCase
{
 
	protected $tenancy = true;
		
	public function test_backup_create_and_restore() {
    	$tenant = tenant('id');
    	
    	$database = TenantHelper::tenant_database ( $tenant);
    	$fullname = TenantHelper::backup_fullname ( $tenant);
    	
    	/*
    	echo "tenant = $tenant\n";
    	echo "database = $database\n";
    	echo "fullname = $fullname\n";
    	*/
    	
    	$this->assertFileDoesNotExist($fullname);
    	$dirname = dirname($fullname);
    	$this->assertDirectoryExists($dirname);
    	rmdir($dirname);
    	$this->assertDirectoryDoesNotExist($dirname);
    	
    	// create the backup
    	$res = BackupHelper::backup($database, $fullname, env('DB_HOST'), env('DB_USERNAME'),  env ('DB_PASSWORD'));    	
    	$this->assertEquals("", $res);
    	$this->assertFileExists($fullname);
    	$this->assertDirectoryExists($dirname);
    	
    	$initial_count = User::count();
    	User::factory()->create();
    	$after_create_count = User::count();
    	$this->assertEquals($initial_count + 1, $after_create_count, "a user has been created");
    	
    	BackupHelper::restore($fullname, $database, true);		// pretend
    	$after_pretend_count = User::count();
    	$this->assertEquals($after_create_count, $after_pretend_count, "no change after pretend");
    	   	
    	BackupHelper::restore($fullname, $database, false);		// restore
    	$after_restore_count = User::count();
    	$this->assertEquals($initial_count, $after_restore_count, "reset after restore");
    	
    	if (file_exists($fullname)) unlink($fullname);
    	$this->assertFileDoesNotExist($fullname);
	}
	
	public function test_incorrect_backup_create() {
		$tenant = tenant('id');
		
		$database = TenantHelper::tenant_database ( $tenant);
		$fullname = TenantHelper::backup_fullname ( $tenant);
				
		$this->assertFileDoesNotExist($fullname);
		// does not create the backup
		$res = BackupHelper::backup("", $fullname, env('DB_HOST'), env('DB_USERNAME'),  env ('DB_PASSWORD'));
		
		$this->assertFileDoesNotExist($fullname);
	}
	
    
}
