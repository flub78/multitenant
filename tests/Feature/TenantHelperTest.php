<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\CommonTenant;

class TenantHelperTest extends TestCase
{
    
    public function test_storage_dirpath() {
    	$tenant = "Abbeville";
    	$central_storage_dirpath = CommonTenant::storage_dirpath();
    	$tenant_storage_dirpath = CommonTenant::storage_dirpath($tenant);
    	
    	$central_len = strlen($central_storage_dirpath);
    	$this->assertEquals(substr($tenant_storage_dirpath, $central_len), "/tenant" . $tenant);
    }
    
    public function test_tenant_database() {
    	$tenant = "Abbeville";
    	
    	$central_db = CommonTenant::tenant_database();
    	$tenant_db = CommonTenant::tenant_database($tenant);
    	
    	$this->assertEquals($tenant_db, "tenant" . $tenant);
    	$this->assertNotEquals($central_db, "");
    	$this->assertEquals($central_db, env ( 'DB_DATABASE' ));
    }
    
    public function test_backup_dirpath(){
    	$this->assertEquals(CommonTenant::backup_dirpath(), CommonTenant::storage_dirpath() . '/app/backup');
    	$tenant = "Abbeville";
    	$this->assertEquals(CommonTenant::backup_dirpath($tenant), CommonTenant::storage_dirpath($tenant) . '/app/backup');
    	
    }
}
