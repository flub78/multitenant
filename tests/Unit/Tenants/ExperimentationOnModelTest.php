<?php

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\Role;
use App\Models\Tenants\Configuration;

/**
 * Experimentations on save method
 * 
 * Conclusion:
 * 
 * 		->make() generates and object where the primary key is not available until save.
 * 
 * 		->save() only returns a boolean
 * 
 * 		->create() generates element where the primary key is not set.
 * 
 * 		So to know exactly what is the key of an object created for test with a factory, the best way is to retrieve it
 * 		with latest()->first();
 * 
 
 * @author frederic
 *
 */
class ExperimentationOnModelTest extends TenantTestCase {
        
    /**
     * Role
     * 		id				bigint		primary key
     * 		name			varchar
     * 		description		varchar
     */
    public function testExpWithIntegerKeyCreate () {
    	                
        // Create an element
        $role1 = Role::factory()->create();
        $this->assertNotNull($role1);
        // var_dump($role1);	// return a full role with an id
        $id_created = $role1->id;
        $this->assertNotNull($id_created);
        
        $latest = Role::latest()->first();
        $id = $latest->id;
        $this->assertTrue(is_int($id));
        $this->assertTrue($id > 0);				// very likely 1 but no time to be sure
        $this->assertEquals($id_created, $id);
    }

    public function testExpWithIntegerKeyMake () {
    	
    	// Make an element
    	$role1 = Role::factory()->make();
    	$this->assertNotNull($role1);
    	// var_dump($role1);
    	$role2 = Role::factory()->make();
    	$this->assertNotNull($role2);
    	
    	$id_made = $role1->id;
    	// as long as the element has not been saved, their is no id
    	$this->assertNull($id_made);	
    	
    	$save_return = $role1->save();
    	$this->assertTrue(is_bool($save_return));
    	$this->assertTrue($save_return);
    	$this->assertFalse(is_bool("2"));
    	
    	// var_dump($role1);		// role1 is still accessible after save
    	$this->assertEquals($role1->id, 1);
    	
    	$save_return2 = $role2->save();
    	$this->assertTrue(is_bool($save_return2));
    	$this->assertTrue($save_return2);
//    	echo "\$save_return2 =$save_return2\n";
    	$this->assertEquals($role2->id, 2);
    	
    	
    	$latest = Role::latest()->first();
    	$id = $latest->id;
    	$this->assertNotNull($id);
    	$this->assertTrue(is_int($id));
    	$this->assertTrue($id > 0);				// very likely 1 but no time to be sure    	
    	
    	$this->assertEquals($save_return, $id);
    }

    // Configuration
    public function testExpWithStringKeyCreate () {
    	
    	// Create an element
    	$conf1 = Configuration::factory()->create();
    	$this->assertNotNull($conf1);
    	// var_dump($conf1);	// return a full role without key
    	$key_created = $conf1->key;
    	$this->assertEquals("0", $key_created);			// That is weird ...
    	$this->assertTrue($conf1->exists);
    	/* 
    	 * especially that a vardump of the object shows a value for key
    	 * 
    	 */
    	
    	$latest = Configuration::latest()->first();
		// latest is a full object with key correctly accessible
    	$key = $latest->key;
    	$this->assertTrue(is_string($key));
    	$this->assertTrue(strlen($key) > 0);				
    }
    
    public function testExpWithStringKeyMake () {
    	
    	// Make an element
    	$conf1 = Configuration::factory()->make();
    	$this->assertNotNull($conf1);
    	// var_dump($conf1);
    	
    	$key_made = $conf1->key;
    	// as long as the element has not been saved, their is no id
    	$this->assertNotNull($key_made);
    	$this->assertTrue(is_string($key_made));
    	$this->assertTrue(strlen($key_made) > 0);
    	
    	$save_return = $conf1->save();
    	// var_dump($conf1);		// $conf1 is valid except the primary key
    	$this->assertTrue(is_bool($save_return));
    	$this->assertTrue($save_return);

    	$latest = Configuration::latest()->first();
    	$key = $latest->key;
    	$this->assertNotNull($key);
    	$this->assertTrue(is_string($key));
    	$this->assertTrue(strlen($key) > 0);				// very likely 1 but no time to be sure

    	// $this->assertEquals($save_return, $key);
    	
    }
    
}
