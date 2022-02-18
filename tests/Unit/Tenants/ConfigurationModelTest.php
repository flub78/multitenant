<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\Configuration;

/**
 * Unit test for Configuration model
 
 * @author frederic
 *
 */
class ConfigurationModelTest extends TenantTestCase {
        
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Configuration::count();
        
        // Create an element
        $configuration1 = Configuration::factory()->create();
        $this->assertNotNull($configuration1);
        $latest = Configuration::latest()->first();
        $key = $latest->key;
        
        // and a second
        Configuration::factory()->create();
        
         // a third to generate values for updates
        $configuration3 = Configuration::factory()->make();
        $this->assertNotNull($configuration3);
        foreach ([ "key", "value" ] as $field) {
            $this->assertNotEquals($latest->$field, $configuration3->$field);
        }
 
        $this->assertTrue(Configuration::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('configurations',  $initial_count + 2);
                        
        # Read
        $stored = Configuration::where(['key' => $key])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the configuration after creation");
        
        foreach ([ "key", "value" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "key", "value" ] as $field) {
            if ($field != "key")
                $stored->$field = $configuration3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = Configuration::where('key', $key)->first();
        $this->assertNotNull($back, "It is possible to retrieve the configuration after update");

        foreach ([ "key", "value" ] as $field) {
            if ($field != "key") {
                $this->assertEquals($back->$field, $configuration3->$field, "$field after update");
                $this->assertDatabaseHas('configurations', [
                    $field => $configuration3->$field,
                ]);
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Configuration::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "key", "value" ] as $field) {
            if ($field != "key")
                $this->assertDatabaseMissing('configurations', [$field => $configuration3->$field]);
        }
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = Configuration::count();
    	
    	$configuration = Configuration::factory()->make();
    	$configuration->key = "999999999";
    	$configuration->delete();
    	
    	$this->assertTrue(Configuration::count() == $initial_count, "No changes in database");
    }
    

}
