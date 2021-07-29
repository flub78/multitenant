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
class TenantHelperTest extends TenantTestCase
{
 
	protected $tenancy = true;
		
	public function test_storage_dirpath() {
    	$tenant = tenant('id');
    	$tenant_storage_dirpath = storage_path();
    	
    	$pos = strpos($tenant_storage_dirpath, "tenant");
    	$this->assertTrue($pos > 0, "tenant in storage path");

    	$pos = strpos($tenant_storage_dirpath, $tenant);
    	$this->assertTrue($pos > 0, "tenant id in storage path");
    }
    
    public function test_tenant_database() {
    	$tenant = tenant('id');
    	
    	$central_db = TenantHelper::tenant_database();
    	$this->assertNotEquals($central_db, "", "$central_db not empty");
    	$this->assertEquals($central_db, env ( 'DB_DATABASE' ));
    	
    	$tenant_db = TenantHelper::tenant_database($tenant);
    	$this->assertEquals($tenant_db, "tenant" . $tenant, "$tenant_db == tenant$tenant");    	
    }
    
    public function test_backup_dirpath(){
    	$tenant = tenant('id');
    	$this->assertEquals(TenantHelper::backup_dirpath($tenant), storage_path() . '/app/backup');
    	
    }
    
    public function test_exists() {
    	$tenant = tenant('id');
    	$this->assertTrue(TenantHelper::exist($tenant), "existing tenant");
    	
    	$this->assertFalse(TenantHelper::exist("non existing"), "non existing tenant");
    }
    
    public function test_routes() {    	
    	$this->assertEquals('http://tenants.com/calendar', route('calendar.index'));
    	$this->assertEquals('http://tenants.com/api/calendar', route('api.calendar.index'));
    }
}
