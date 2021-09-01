<?php

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\UserRole;
use App\Models\Tenants\Role;
use App\Models\User;
use Illuminate\Database\QueryException;

/**
 * UserRole model unit test
 * 
 * @author frederic
 *
 */
class UserRoleModelTest extends TenantTestCase

{
	public function setUp(): void
	{
		parent::setUp();
		
		// create a few roles and users	
		$this->user1 = User::factory()->create();
		$this->user2 = User::factory()->create();
		$this->user3 = User::factory()->create();

		$this->role1 = Role::factory()->create(['name' => 'redactor']);
		$this->role2 = Role::factory()->create(['name' => 'guest']);
		$this->role3 = Role::factory()->create(['name' => 'superuser']);
		$this->role4 = Role::factory()->create(['name' => 'root']);
	}
	
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = UserRole::count();
        
        // Create
        $user_role1 = UserRole::factory()->make(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
        $user_role1->save();   // set $user_role to null
        
        // and a second
        $role2 = UserRole::factory()->make(['user_id' => $this->user1->id, 'role_id' => $this->role2->id]);
        $role2->save();   // set $role to null
        
        $count = UserRole::count();
        $this->assertTrue($count == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('user_roles',  $initial_count + 2);
                        
        # Read
        $stored = UserRole::where(['user_id' => $this->user1->id])->first();
        
        $this->assertNotNull($stored, "It is possible to retrieve the user_role");
        
        $this->assertEquals($this->user1->id, $stored->user_id, "Checks the element fetched from the database");
        
        // Update
        $new_role_id = $this->role4->id;
        $stored->role_id = $new_role_id;
        
        $id = $stored->id;
        
        $stored->update();
        
        $back = UserRole::find($id);
        $this->assertEquals($back->user_id, $stored->user_id, "After update");
        $this->assertEquals($back->role_id, $stored->role_id, "After update");
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $count = UserRole::count();
        $this->assertTrue($count == $initial_count + 1, "One less elements in the table");
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = UserRole::count();
    	
    	$user_role = UserRole::factory()->make();
    	$user_role->id = 999999999;
    	$user_role->delete();
    	
    	$count = UserRole::count();
    	$this->assertTrue($count == $initial_count, "No changes in database");
    }
    
    public function test_computed_attributes() {
    	
    	$user_role = UserRole::factory()->make(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
    	
    	$this->assertEquals($user_role->user_id, $this->user1->id);
    	$this->assertEquals($user_role->role_id, $this->role1->id);
    	
    	$user1_name = $this->user1->full_name;
    	$role1_name = $this->role1->full_name;
    	$this->assertEquals($user_role->user_name, $user1_name);
    	$this->assertEquals($user_role->role_name, $role1_name);
    	
    	$fullname = $user_role->full_name;
    	$this->assertEquals(__('user_roles.fullname', ['role' => $role1_name, 'user' => $user1_name]), $fullname);    	
    }
    
    public function test_cascade () {
    	$initial_count = UserRole::count();
    	$this->assertEquals(0, $initial_count, "$initial_count == 0");
    	
    	UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
    	UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role2->id]);
    	UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role3->id]);

    	UserRole::factory()->create(['user_id' => $this->user2->id, 'role_id' => $this->role1->id]);
    	UserRole::factory()->create(['user_id' => $this->user2->id, 'role_id' => $this->role2->id]);

    	UserRole::factory()->create(['user_id' => $this->user3->id, 'role_id' => $this->role4->id]);

    	$this->assertEquals(6, UserRole::count(), "after create");
    	
    	$this->role4->delete();
    	$this->assertEquals(5, UserRole::count(), "after role delete");

    	$this->user1->delete();
    	$this->assertEquals(2, UserRole::count(), "after user delete");	
    }
    
    public function test_duplicate_are_rejected () {
    	$initial_count = UserRole::count();
    	$this->assertEquals(0, UserRole::count(), "after init");
    	
    	$user_role1 = UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
    	$this->assertEquals(1, UserRole::count(), "after creation");
    	try {
    		$role2 = UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
    		$this->assertTrue(false, "Exception not raised on creating duplicate");
    	} catch (QueryException $e) {
    		$this->assertTrue(true, "Exception raised on creating duplicate");
    	}
    	$this->assertEquals(1, UserRole::count(), "after duplicate creation");  	
    }
    
    public function test_foreign_keys () {
    	// attempts to create user_role with incorrect indexes should be rejected
    	try {
    		UserRole::factory()->create(['user_id' => $this->user1->id + 1000000000, 'role_id' => $this->role1->id]);
    		$this->assertTrue(false, "Exception not raised on referencing wrong user_id");
    	} catch (QueryException $e) {
    		$this->assertTrue(true, "Exception raised on referencing wrong user_id");
    	}
    
    	try {
    		UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role1->id + 1000000000]);
    		$this->assertTrue(false, "Exception not raised on referencing wrong role_id");
    	} catch (QueryException $e) {
    		$this->assertTrue(true, "Exception raised on referencing wrong role_id");
    	}
    }
    
    public function test_has_role() {
    	
    	$this->assertFalse(UserRole::hasRole($this->user1, "player"));
    	UserRole::factory()->create(['user_id' => $this->user2->id, 'role_id' => $this->role2->id]);
    	$this->assertTrue(UserRole::hasRole($this->user2, "guest"));
    	$this->assertFalse(UserRole::hasRole($this->user2, "redactor"));
    	
    }
}
