<?php

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\Role;

/**
 * To adapt to another Model
 * rename the class
 * Replace the class name Role:: per ...
 * Replace the element name role per
 * replace the field names name and description per ...
 * @author frederic
 *
 */
class RoleModelTest extends TenantTestCase

{
        
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Role::count();
        
        // Create
        $role = Role::factory()->make(['name' => 'redactor', 'description' => 'Redactor role']);
        $name = $role->name;
        $description = $role->description;
        $role->save();   // set $role to null
        
        // and a second
        $config2 = Role::factory()->make(['name' => 'accounter', 'description' => 'Accounter role']);
        $config2->save();
        
        $this->assertTrue(Role::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('roles',  $initial_count + 2);
                        
        # Read
        $stored = Role::where(['name' => $name])->first();
        
        $this->assertNotNull($stored, "It is possible to retrieve the role");
        
        $this->assertEquals($name, $stored->name, "Checks the element fetched from the database");
        $this->assertEquals($description, $stored->description, "Checks the element fetched from the database");
        
        // Update
        $new_description = "updated description";
        $stored->description = $new_description;
        
        $stored->update();
        
        $back = Role::where('name', $name)->first();
        $this->assertEquals($back->description, $new_description, "After update");
        $this->assertDatabaseHas('roles', [
            'description' => $new_description,
        ]);
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Role::count() == $initial_count + 1, "One less elements in the table");
        $this->assertDatabaseMissing('roles', [
            'description' => $new_description,
        ]);
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = Role::count();
    	
    	$role = Role::factory()->make();
    	$role->name = "999999999";
    	$role->delete();
    	
    	$this->assertTrue(Role::count() == $initial_count, "No changes in database");
    }
    
    public function test_computed_attributes() {
    	$role = Role::factory()->create(['name' => 'musician', 'description' => 'plays the guitar']);
    	$role2 = Role::factory()->create(['name' => 'serial killer', 'description' => 'virus']);
    	
    	$this->assertEquals($role->name, $role->image(), "image");
    	
    	$selector = Role::selector();
    	$this->assertEquals(2, count($selector));
    	$this->assertEquals($role->id, $selector[0]['id']);
    	$this->assertEquals($role2->image(), $selector[1]['name']);
    	
    	$selector2 = Role::selector(['id' => $role2->id]);
    	$this->assertEquals(1, count($selector2));
    }
    
    public function test_factory() {
    	$role = Role::factory()->make();
    	$this->assertNotEquals('', $role->name);
    	$this->assertNotEquals('', $role->description);
    	
    	echo "\nrole=" . $role->name . ", description=" . $role->description . "\n";
    }
    
}
