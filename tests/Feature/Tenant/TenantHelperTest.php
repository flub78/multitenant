<?php

namespace Tests\Feature;

use Tests\TenantTestCase;
use App\Helpers\TenantHelper;

class TenantHelperTest extends TenantTestCase
{
 
	protected $tenancy = true;
	
    public function test_storage_dirpath() {
    	$tenant = tenant('id');
    	echo "tenant = $tenant";
    	$central_storage_dirpath = TenantHelper::storage_dirpath();
    	$tenant_storage_dirpath = TenantHelper::storage_dirpath($tenant);
    	
    	echo "central storage dirpath = $central_storage_dirpath\n";
    	echo "tenant storage dirpath = $tenant_storage_dirpath\n";
    	
    	$central_len = strlen($central_storage_dirpath);
    	$this->assertEquals(substr($tenant_storage_dirpath, $central_len), "/tenant" . $tenant);
    }
    
    public function test_tenant_database() {
    	$tenant = tenant('id');
    	
    	$central_db = TenantHelper::tenant_database();
    	$this->assertNotEquals($central_db, "", "$central_db not empty");
    	
    	$tenant_db = TenantHelper::tenant_database($tenant);
    	$this->assertEquals($central_db, env ( 'DB_DATABASE' ));
    	$this->assertEquals($tenant_db, "tenant" . $tenant, "$tenant_db == tenant$tenant");
    }
    
    public function test_backup_dirpath(){
    	$this->assertEquals(TenantHelper::backup_dirpath(), TenantHelper::storage_dirpath() . '/app/backup');
    	$tenant = tenant('id');
    	$this->assertEquals(TenantHelper::backup_dirpath($tenant), TenantHelper::storage_dirpath($tenant) . '/app/backup');
    	
    }
}
