<?php

namespace Tests\Feature;

use Tests\TenantTestCase;
use App\Helpers\TenantHelper;
use App\Helpers\DirHelper;

/**
 * Test TenantHelper in a tenant context
 * @author frederic
 *
 */
class CentralTenantHelperTest extends TenantTestCase
{
 
	protected $tenancy = false;
		
	public function test_storage_dirpath() {
    	$tenant = "Abbeville";
    	$tenant_storage_dirpath = TenantHelper::storage_dirpath($tenant);
    	echo $tenant_storage_dirpath;
    	$pos = strpos($tenant_storage_dirpath, "tenant");
    	$this->assertTrue($pos > 0, "tenant in storage path");

    	$pos = strpos($tenant_storage_dirpath, $tenant);
    	$this->assertTrue($pos > 0, "tenant id in storage path");
    	
    	$storage_dirpath = TenantHelper::storage_dirpath("");
    	$this->assertEquals(storage_path(), $storage_dirpath);
    }
    
    public function test_tenant_database() {
    	$tenant = "Abbeville";
    	
    	$central_db = TenantHelper::tenant_database();
    	$this->assertNotEquals($central_db, "", "$central_db not empty");
    	$this->assertEquals($central_db, env ( 'DB_DATABASE' ));
    	
    	$tenant_db = TenantHelper::tenant_database($tenant);
    	$this->assertEquals($tenant_db, "tenant" . $tenant, "$tenant_db == tenant$tenant");    	
    }
    
    public function test_backup_dirpath(){
    	$this->assertEquals(TenantHelper::backup_dirpath(), storage_path() . '/app/backup');
    	
    	$tenant = "Abbeville";
    	$this->assertEquals(TenantHelper::backup_dirpath($tenant), storage_path() . "/tenant$tenant/app/backup");
    	
    }
    
    public function test_tenant_id_list() {
    	$list = TenantHelper::tenant_id_list();
    	$this->assertIsArray($list);
    }
}
