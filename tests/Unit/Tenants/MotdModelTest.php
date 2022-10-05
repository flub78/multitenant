<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\Motd;
use App\Helpers\CodeGenerator as CG;

/**
 * Unit test for Motd model
 
 * @author frederic
 *
 */
class MotdModelTest extends TenantTestCase {
        
   /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Motd::count();
        
        // Create an element
        $motd1 = Motd::factory()->create();
        $this->assertNotNull($motd1);
        $latest = Motd::latest()->first();
        $id = $latest->id;
        
        // and a second
        Motd::factory()->create();
        
         // a third to generate values for updates
        $motd3 = Motd::factory()->make();
        $this->assertNotNull($motd3);
        $table = "motds";
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if (CG::lot_of_values($table, $field))
                $this->assertNotEquals($latest->$field, $motd3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(Motd::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('motds',  $initial_count + 2);
                        
        # Read
        $stored = Motd::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the motd after creation");
        
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "id")
                $stored->$field = $motd3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = Motd::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the motd after update");

        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $motd3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Motd::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "id" && (CG::lot_of_values($table, $field)) )
                $this->assertDatabaseMissing('motds', [$field => $motd3->$field]);
        }
    }
    
    
    /**
     * Test delete
     * Given the database server is on
     * And the schema exists in database
     * When deleting a non exiting element
     * Then nothing change in database
     */
    public function test_deleting_non_existing_element () {
    	$initial_count = Motd::count();
    	
    	$motd = Motd::factory()->make();
    	$motd->id = "999999999";
    	$motd->delete();
    	
    	$this->assertTrue(Motd::count() == $initial_count, "No changes in database");
    }
    
}
