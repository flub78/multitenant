<?php

namespace tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tenants\CalendarEvent;

class CalendarEventModelTest extends TestCase
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
    	        
        $initial_count = CalendarEvent::count();
        
        // Create
        $user = CalendarEvent::factory()->make();        
        $user->save();
        
        // and a second
        CalendarEvent::factory()->make()->save();
        
        $count = CalendarEvent::count();
        $this->assertTrue($count == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('users',  $initial_count + 2);
                
        # Read
        $stored = CalendarEvent::where('name', $user->name)->first();
        
        $this->assertTrue($user->equals($stored), "Checks the element fetched from the database");
        
        // Update
        $new_name = "updated user";
        $new_email = 12;
        $stored->name = $new_name;
        $stored->email = $new_email;
        
        $stored->save();
        
        $back = CalendarEvent::where('name', $new_name)->first();
        $this->assertEquals($back->email, $new_email, "After update");
        $this->assertEquals($back->isAdmin(), false, "Not admin by default");
        $this->assertDatabaseHas('users', [
            'name' => $new_name,
        ]);
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $count = CalendarEvent::count();
        $this->assertTrue($count == $initial_count + 1, "One less elements in the table");
        $this->assertDatabaseMissing('users', [
            'name' => $new_name,
        ]);
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = CalendarEvent::count();
    	
    	$user = CalendarEvent::factory()->make();
    	$user->id = 999999999;
    	$user->delete();
    	
    	$count = CalendarEvent::count();
    	$this->assertTrue($count == $initial_count, "No changes in database");
    }
}
