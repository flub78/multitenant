<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\CodeGenType;

/**
 * Unit test for CodeGenType model
 
 * @author frederic
 *
 */
class CodeGenTypeModelTest extends TenantTestCase {
	
        	
	/**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = CodeGenType::count();
       
        // Create an element
        $code_gen_type1 = CodeGenType::factory()->create();
        $this->assertNotNull($code_gen_type1);
        $latest = CodeGenType::latest()->first();
        $id = $latest->id;
        
        // and a second
        CodeGenType::factory()->create();
        
         // a third to generate values for updates
        $code_gen_type3 = CodeGenType::factory()->make();
        $this->assertNotNull($code_gen_type3);
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "color_name", "picture", "attachment" ] as $field) {
            $this->assertNotEquals($latest->$field, $code_gen_type3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(CodeGenType::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('code_gen_types',  $initial_count + 2);
                        
        # Read
        $stored = CodeGenType::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the code_gen_type after creation");
        
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "black_and_white", "color_name", "picture", "attachment" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "black_and_white", "color_name", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $stored->$field = $code_gen_type3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = CodeGenType::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the code_gen_type after update");

        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "black_and_white", "color_name", "picture", "attachment" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $code_gen_type3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(CodeGenType::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "color_name", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $this->assertDatabaseMissing('code_gen_types', [$field => $code_gen_type3->$field]);
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
    	$initial_count = CodeGenType::count();
    	
    	$code_gen_type = CodeGenType::factory()->make();
    	$code_gen_type->id = "999999999";
    	$code_gen_type->delete();
    	
    	$this->assertTrue(CodeGenType::count() == $initial_count, "No changes in database");
    }
    
}
