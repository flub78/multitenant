<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\Attachment;
use App\Helpers\CodeGenerator as CG;

/**
 * Unit test for Attachment model
 
 * @author frederic
 *
 */
class AttachmentModelTest extends TenantTestCase {
        
   /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Attachment::count();
        
        // Create an element
        $attachment1 = Attachment::factory()->create();
        $this->assertNotNull($attachment1);
        $latest = Attachment::latest()->first();
        $id = $latest->id;
        
        // and a second
        Attachment::factory()->create();
        
         // a third to generate values for updates
        $attachment3 = Attachment::factory()->make();
        $this->assertNotNull($attachment3);
        $table = "attachments";
        foreach ([ "referenced_table", "referenced_id", "user_id", "description", "file" ] as $field) {
            if (CG::lot_of_values($table, $field))
                $this->assertNotEquals($latest->$field, $attachment3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(Attachment::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('attachments',  $initial_count + 2);
                        
        # Read
        $stored = Attachment::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the attachment after creation");
        
        foreach ([ "referenced_table", "referenced_id", "user_id", "description", "file" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "referenced_table", "referenced_id", "user_id", "description", "file" ] as $field) {
            if ($field != "id")
                $stored->$field = $attachment3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = Attachment::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the attachment after update");

        foreach ([ "referenced_table", "referenced_id", "user_id", "description", "file" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $attachment3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertModelMissing($stored);
        $this->assertTrue(Attachment::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "referenced_table", "referenced_id", "user_id", "description", "file" ] as $field) {
            if ($field != "id" && (CG::lot_of_values($table, $field)) )
                $this->assertDatabaseMissing('attachments', [$field => $attachment3->$field]);
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
    	$initial_count = Attachment::count();
    	
    	$attachment = Attachment::factory()->make();
    	$attachment->id = "999999999";
    	$attachment->delete();
    	
    	$this->assertTrue(Attachment::count() == $initial_count, "No changes in database");
    }
    
}
