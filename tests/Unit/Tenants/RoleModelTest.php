<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\Role;

/**
 * Unit test for Role model
 
 * @author frederic
 *
 */
class RoleModelTest extends TenantTestCase {
        
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Role::count();
        
        // Create an element
        $role1 = Role::factory()->create();
        $this->assertNotNull($role1);
        $id = $role1->id;
                
        // and a second
        Role::factory()->create();

        // a third to generate values for updates
        $role3 = Role::factory()->make();
        foreach (["name", "description"] as $field) {
        	$this->assertNotEquals($role1->$field, $role3->$field);
        }
        
        $this->assertTrue(Role::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('roles',  $initial_count + 2);
                        
        # Read
        $stored = Role::where(['id' => $id])->first();
        
        $this->assertNotNull($stored, "It is possible to retrieve the role");
        
        foreach (["name", "description"] as $field) {
        	$this->assertEquals($role1->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach (["name", "description"] as $field) {
        	$stored->$field = $role3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = Role::where('id', $id)->first();
        foreach (["name", "description"] as $field) {
        	$this->assertEquals($back->$field, $role3->$field, "$field after update");
        	$this->assertDatabaseHas('roles', [
            	$field => $role3->$field,
        	]);
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Role::count() == $initial_count + 1, "One less elements in the table");
        foreach (["name", "description"] as $field) {
        	$this->assertDatabaseMissing('roles', [$field => $role3->$field]);
        }
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = Role::count();
    	
    	$role = Role::factory()->make();
    	$role->id = "999999999";
    	$role->delete();
    	
    	$this->assertTrue(Role::count() == $initial_count, "No changes in database");
    }
    
    public function test_computed_attributes() {
    	$role = Role::factory()->create();
    	$role2 = Role::factory()->create();
    	
    	$this->assertNotEquals('', $role->image(), "image");
    	
    	$selector = Role::selector();
    	$this->assertEquals(2, count($selector));
    	$this->assertEquals($role->id, $selector[0]['id']);
    	$this->assertEquals($role2->image(), $selector[1]['name']);
    	
    	$selector2 = Role::selector(['id' => $role2->id]);
    	$this->assertEquals(1, count($selector2));
    }    
}
