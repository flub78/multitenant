<?php

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\Configuration;

class ConfigurationModelTest extends TenantTestCase

{
    // Clean up the database
    // not for tenants
    // use RefreshDatabase;
        
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Configuration::count();
        
        // Create
        $configuration = Configuration::factory()->make();
        $key = $configuration->key;
        $value = $configuration->value;
        $configuration->save();   // set $configuration to null
        
        // and a second
        $config2 = Configuration::factory()->make();
        $config2->save();
        
        $this->assertTrue(Configuration::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('configurations',  $initial_count + 2);
                        
        # Read
        $stored = Configuration::where(['key' => $key])->first();
        
        $this->assertNotNull($stored, "It is possible to retrieve the configuration");
        
        $this->assertEquals($key, $stored->key, "Checks the element fetched from the database");
        $this->assertEquals($value, $stored->value, "Checks the element fetched from the database");
        
        // Update
        $new_value = "updated value";
        $stored->value = $new_value;
        
        $stored->update();
        
        $back = Configuration::where('key', $key)->first();
        $this->assertEquals($back->value, $new_value, "After update");
        $this->assertDatabaseHas('configurations', [
            'value' => $new_value,
        ]);
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Configuration::count() == $initial_count + 1, "One less elements in the table");
        $this->assertDatabaseMissing('configurations', [
            'value' => $new_value,
        ]);
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = Configuration::count();
    	
    	$configuration = Configuration::factory()->make();
    	$configuration->key = "999999999";
    	$configuration->delete();
    	
    	$this->assertTrue(Configuration::count() == $initial_count, "No changes in database");
    }
}
