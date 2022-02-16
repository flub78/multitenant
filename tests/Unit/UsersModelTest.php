<?php

namespace tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UsersModelTest extends TestCase
{
    // Clean up the database
    use RefreshDatabase;
        
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = User::count();
        
        // Create 2 users
        $user = User::factory()->make();        
        $user2 = User::factory()->make();
        $this->assertFalse($user->equals($user2));
        
        $this->assertTrue($user->isActive());

        $user->save();
        $user2->save();
        
        $this->assertTrue(User::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('users',  $initial_count + 2);
                
        # Read
        $stored = User::where('name', $user->name)->first();
        
        $this->assertTrue($user->equals($stored), "Checks the element fetched from the database");
        
        // Update
        $new_name = "updated user";
        $new_email = 12;
        $stored->name = $new_name;
        $stored->email = $new_email;
        
        $stored->save();
        
        $back = User::where('name', $new_name)->first();
        $this->assertEquals($back->email, $new_email, "After update");
        $this->assertEquals($back->isAdmin(), false, "Not admin by default");
        $this->assertDatabaseHas('users', [
            'name' => $new_name,
        ]);
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(User::count() == $initial_count + 1, "One less elements in the table");
        $this->assertDatabaseMissing('users', [
            'name' => $new_name,
        ]);
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = User::count();
    	
    	$user = User::factory()->make();
    	$user->id = 999999999;
    	$user->delete();
    	
    	$this->assertTrue(User::count() == $initial_count, "No changes in database");
    }
    
    public function test_computed_attributes() {
    	$user = User::factory()->create();
    	$user2 = User::factory()->create();
    	
    	$this->assertEquals($user->name, $user->full_name, "full_name");
    	
    	$selector = User::selector();
    	$this->assertEquals(2, count($selector));
    	$this->assertEquals($user->id, $selector[0]['id']);
    	$this->assertEquals($user2->full_name, $selector[1]['name']);
    	
    	$selector2 = User::selector(['id' => $user2->id]);
    	$this->assertEquals(1, count($selector2));    	
    }
}
