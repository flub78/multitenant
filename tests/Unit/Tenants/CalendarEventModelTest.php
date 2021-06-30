<?php

namespace tests\Unit;

use Tests\TenantTestCase;
use App\Helpers\Config;

use App\Models\Tenants\CalendarEvent;
use Carbon\Carbon;
// use database\factories\CalendarEventFactory;

/**
 * Unit test for CalendarEventModel
 * 
 * https://carbon.nesbot.com/docs/
 * 
 * @author frederic
 *
 */
class CalendarEventModelTest extends TenantTestCase

{
	public function assertEqualDates($date1, $date2, $comment = "") {
		$d1 = Date::createFromFormat('Y-m-d', $date1);
		
		var_dump($d1); exit;
		$d2 = strtotime(date_format($date2, 'Y-m-d'));
		return $this->Equals($d1, $d2, $comment);
	}
        
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
        $event = CalendarEvent::factory()->make(['start' => '2021-06-30']);        
        $event->save();
        
        // and a second
        CalendarEvent::factory()->make()->save();
        
        $count = CalendarEvent::count();
        $this->assertTrue($count == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('calendar_events',  $initial_count + 2);
                
        # Read
        $stored = CalendarEvent::where('id', $event->id)->first();
        
        $this->assertEquals($event->title, $stored->title);
        // $this->assertEqualDates($event->start, $stored->start);
        // $this->assertEqualDates($event->end, $stored->end);
        
        // Update
        $new_title = "updated title";
        $new_backgroundColor = "red";
        $stored->title = $new_title;
        $stored->backgroundColor = $new_backgroundColor;
        
        $stored->save();
        
        $back = CalendarEvent::where('id', $event->id)->first();
        $this->assertEquals($back->title, $new_title, "After update");
        $this->assertEquals($back->backgroundColor, $new_backgroundColor, "Updated color");
        $this->assertDatabaseHas('calendar_events', [
            'title' => $new_title,
        ]);
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $count = CalendarEvent::count();
        $this->assertTrue($count == $initial_count + 1, "One less elements in the table");
        $this->assertDatabaseMissing('calendar_events', [
            'title' => $new_title,
        ]);
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = CalendarEvent::count();
    	
    	$event = CalendarEvent::factory()->make();
    	$event->id = 999999999;
    	$event->delete();
    	
    	$count = CalendarEvent::count();
    	$this->assertTrue($count == $initial_count, "No changes in database");
    }
    
    public function test_carbon() {
    	$mutable = Carbon::now();
    	$mutable->add(1, 'day');
    	var_dump($mutable->isoFormat('dddd D'));  
    	
    	$this->assertNotNull($mutable);
    	
    	Config::set('app.timezone', 'Europe/Paris');
    	$tz = Config::config('app.timezone');
    	echo "Timezone = $tz\n";
    	
    }
}
