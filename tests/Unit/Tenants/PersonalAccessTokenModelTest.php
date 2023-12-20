<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\PersonalAccessToken;
use App\Helpers\CodeGenerator as CG;

/**
 * Unit test for PersonalAccessToken model
 
 * @author frederic
 *
 */
class PersonalAccessTokenModelTest extends TenantTestCase {
        
   /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = PersonalAccessToken::count();
        
        // Create an element
        $personal_access_token1 = PersonalAccessToken::factory()->create();
        $this->assertNotNull($personal_access_token1);
        $latest = PersonalAccessToken::latest()->first();
        $id = $latest->id;
        
        // and a second
        PersonalAccessToken::factory()->create();
        
         // a third to generate values for updates
        $personal_access_token3 = PersonalAccessToken::factory()->make();
        $this->assertNotNull($personal_access_token3);
        $table = "personal_access_tokens";
        foreach ([ "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at" ] as $field) {
            if (CG::lot_of_values($table, $field))
                $this->assertNotEquals($latest->$field, $personal_access_token3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(PersonalAccessToken::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('personal_access_tokens',  $initial_count + 2);
                        
        # Read
        $stored = PersonalAccessToken::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the personal_access_token after creation");
        
        foreach ([ "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at" ] as $field) {
            if ($field != "id")
                $stored->$field = $personal_access_token3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = PersonalAccessToken::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the personal_access_token after update");

        foreach ([ "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $personal_access_token3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertModelMissing($stored);
        $this->assertTrue(PersonalAccessToken::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at" ] as $field) {
            if ($field != "id" && (CG::lot_of_values($table, $field)) )
                $this->assertDatabaseMissing('personal_access_tokens', [$field => $personal_access_token3->$field]);
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
    	$initial_count = PersonalAccessToken::count();
    	
    	$personal_access_token = PersonalAccessToken::factory()->make();
    	$personal_access_token->id = "999999999";
    	$personal_access_token->delete();
    	
    	$this->assertTrue(PersonalAccessToken::count() == $initial_count, "No changes in database");
    }
    
}
