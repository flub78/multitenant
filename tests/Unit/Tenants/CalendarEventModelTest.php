<?php

namespace tests\Unit;

use Tests\TenantTestCase;
use App\Helpers\Config;

use App\Models\Tenants\CalendarEvent;
use Carbon\Carbon;
// use DateTime;
// use DateTimeZone;
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
		
		if ($date1 == "" && $date2 == "") return true;
		
		$d1 = Carbon::createFromFormat('Y-m-d H:i:s', $date1);	
		$d2 = Carbon::createFromFormat('Y-m-d H:i:s', $date2);
		
		$this->assertTrue($d1->EqualTo($d2), $d1 . " == " . $d2);
	}
        

	/**
	 * Test Calendar Even accessors
	 */
	public function test_accessor() {
		$event = CalendarEvent::factory()->create(['start' => '2021-06-30 12:00:00']);
		
		$this->assertNotNull($event);
		
		$this->assertEquals('30/06/2021', $event->getStartDate());

		$this->assertEquals('12:00', $event->getStartTime());

		$this->assertEquals('', $event->getEndDate());
		
		$this->assertEquals('', $event->getEndTime() );		
	}
	
	/**
     * Test element creation, read, update and delete
     * Given the database server is on
     * And the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = CalendarEvent::count();
        
        // Create
        $event = CalendarEvent::factory()->make(['start' => '2021-06-30 12:00:00']);        
        $event->save();
        
        // and a second
        CalendarEvent::factory()->make()->save();
        
        $count = CalendarEvent::count();
        $this->assertTrue($count == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('calendar_events',  $initial_count + 2);
                
        # Read
        $stored = CalendarEvent::where('id', $event->id)->first();
        
        $this->assertEquals($event->title, $stored->title);
        $this->assertEqualDates($event->start, $stored->start);
        $this->assertEqualDates($event->end, $stored->end);
        
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
    
    
    /**
     * Test delete
     * Given the database server is on
     * And the schema exists in database
     * When deleting a non exiting element
     * Then nothing change in database
     */
    public function test_deleting_non_existing_element () {
    	$initial_count = CalendarEvent::count();
    	
    	$event = CalendarEvent::factory()->make();
    	$event->id = 999999999;
    	$event->delete();
    	
    	$count = CalendarEvent::count();
    	$this->assertTrue($count == $initial_count, "No changes in database");
    }
    
    /**
     * Not to test the carbon library, but to learn how to use it
     * https://carbon.nesbot.com/docs/
     */
    public function test_carbon() {
    	
    	$date_regexp = '/(\d{4})\-(\d{2})\-(\d{2})\s(\d{2})\:(\d{2})\:(\d{2})/i';
    	
    	$mutable = Carbon::now();
    	$mutable->add(1, 'day');
    	$this->assertEquals(0, $mutable->utcOffset());
    	$this->assertMatchesRegularExpression($date_regexp,$mutable);
    	
    	$this->assertEquals($mutable, $mutable->toDateTimeString(), "2021-07-01 17:59:55");		
    	
    	// today, tomorrow, yesterday are at 00:00:00.000000+0000
    	$this->assertEquals(Carbon::today()->add(1, 'day'), Carbon::tomorrow());
    	
    	$this->assertNotNull($mutable);
    	
    	Config::set('app.timezone', 'Europe/Paris');
    	$tz = Config::config('app.timezone');
    	    	
    	// now in local timezone
    	$date = Carbon::now($tz);
    	$this->assertMatchesRegularExpression($date_regexp,$date, 'By default TZ is not displayed in Carbon date');
    	$this->assertEquals($tz, $date->tzName, "TimeZone is contained in the object");
    	// echo "date = $date\n";		date = 2021-06-30 18:50:01
		
    	// by default now is in UTC
    	$date = Carbon::now();
    	$this->assertEquals(0, Carbon::now()->utcOffset());
 
    	// At least in summer Paris is 2 hours ahead of UTC
    	$this->assertTrue(Carbon::now($tz)->utcOffset() >= 60);		// True east of UTC    	
    	
    	$this->assertEquals('9999-12-31 23:59:59', Carbon::maxValue());
    	$this->assertEquals('0001-01-01 00:00:00', Carbon::minValue());
    	
    	$seed_date = '1975-05-21';
    	$a_long_time_ago = Carbon::createFromFormat('Y-m-d', $seed_date, $tz);		// 1975-05-21 20:16:3110
    	$this->assertEquals($tz, $a_long_time_ago->tzName, "TimeZone is contained in the object");
	   	$this->assertEquals(strlen($a_long_time_ago), strlen($seed_date) + 9, 'carbon date contains time');
	   	
	   	// Set Carbon for testing
	   	$knownDate = Carbon::create(2001, 5, 21, 12, 0, 0, $tz); 
	   	Carbon::setTestNow($knownDate);    							
	   	$now = Carbon::now();
	   	
	   	// getters
	   	$this->assertEquals("2001-05-21 12:00:00", $now);
	   	$this->assertEquals("2001", $now->year);
	   	$this->assertEquals("05", $now->month);
	   	$this->assertEquals("21", $now->day);
	   	$this->assertEquals("12", $now->hour);
	   	$this->assertEquals("00", $now->minute);
	   	$this->assertEquals("00", $now->second);
	   	$this->assertEquals("lundi", $now->locale('fr')->dayName);
	   	$this->assertEquals("mai", $now->locale('fr')->monthName);
	   	
	   	$this->assertFalse(Carbon::now()->utc);
	   	$this->assertEquals($tz, Carbon::now()->timezone);
	   	$this->assertEquals(Carbon::now()->tz, Carbon::now()->timezone);
	   	
	   	// take a UTC time
	   	$utc = Carbon::now('UTC');
	   	$this->assertEquals('UTC', $utc->tz);

	   	// Conversion from UTC to local
	   	// ============================
	   	// create a local time from the first one
	   	// $local = $utc;		Wrong, would only copy the object reference
	    // $local = Carbon::create($utc->toDateTimeString());		// deep copy, but not efficient
	   	// $local = Carbon::createFromDate($utc);  // error
	   	$local = clone $utc;		// efficient deep copy
	   	
	   	$local->tz($tz);		// force another timezone, forcing a timezone adapt hour and maybe day
	   	
	   	$this->assertEquals('UTC', $utc->tz);		// one date is in UTC
	   	$this->assertEquals($tz, $local->tz);		// the other in Europe/Paris

	   	$this->assertEquals(10, $utc->hour);		// They do not have the same hour
	   	$this->assertEquals(12, $local->hour);

	   	$this->assertEquals(0, $utc->diffInSeconds($local));	// But they are the same time
	   	$this->assertTrue($utc->EqualTo($local)); 				// More efficient way to compare
	   	
	   	// New year eve
	   	$utc->year(1975)->month(12)->day(31)->hour(23)->minute(00)->second(0);
	   	// Paris is already in the next year
	   	$utc->tz($tz);
	   	$this->assertEquals(1976, $utc->year);
	   	$this->assertEquals(1, $utc->month);
	   	$this->assertEquals(1, $utc->day);
	   	$this->assertEquals(0, $utc->hour);
	   	
	   	
	   	// Equivalent setters exist
	   	$now->month = 4;
	   	$this->assertEquals("avril", $now->locale('fr')->monthName);
	   	
	   	// they can be chained
	   	$now->year(1975)->month(5)->day(21)->hour(22)->minute(32)->second(5);
	   	$this->assertEquals("1975-05-21 22:32:05", $now->toDateTimeString()); // toDateTimeString is mandatory, it is only implicitely called in string context

	   	$this->assertEquals("Wednesday 21st of May 1975 10:32:05 PM", $now->format('l jS \\of F Y h:i:s A'));
	   	
	   	// echo $now->serialize();
	   	$this->assertEquals('"1975-05-21T21:32:05.000000Z"', json_encode($now));
	   	
	   	// Back to real time
	   	Carbon::setTestNow(); 
	   	$this->assertNotEquals("2001-05-21 12:00:00", Carbon::now());
	   	
	   	$this->assertEquals('UTC', Carbon::now()->tzName);
	   	$first = Carbon::create(2012, 9, 5, 20, 26, 11);
	   	$second = Carbon::create(2012, 9, 5, 20, 26, 11, $tz);
	   	$second->add(2, 'hour');
	   	$this->assertEquals(0, $first->diffInSeconds($second));
	   	
	   	$second->subHour(2);
	   	$this->assertEquals("2 heures avant", $second->locale('fr')->diffForHumans($first));
	   	
	   	/*
	   	echo "\n";
	   	// These getters specifically return integers, ie intval()
	   	var_dump(Carbon::SUNDAY);                          // int(0)
	   	var_dump(Carbon::MONDAY);                          // int(1)
	   	var_dump(Carbon::TUESDAY);                         // int(2)
	   	var_dump(Carbon::WEDNESDAY);                       // int(3)
	   	var_dump(Carbon::THURSDAY);                        // int(4)
	   	var_dump(Carbon::FRIDAY);                          // int(5)
	   	var_dump(Carbon::SATURDAY);                        // int(6)
	   	
	   	var_dump(Carbon::YEARS_PER_CENTURY);               // int(100)
	   	var_dump(Carbon::YEARS_PER_DECADE);                // int(10)
	   	var_dump(Carbon::MONTHS_PER_YEAR);                 // int(12)
	   	var_dump(Carbon::WEEKS_PER_YEAR);                  // int(52)
	   	var_dump(Carbon::DAYS_PER_WEEK);                   // int(7)
	   	var_dump(Carbon::HOURS_PER_DAY);                   // int(24)
	   	var_dump(Carbon::MINUTES_PER_HOUR);                // int(60)
	   	var_dump(Carbon::SECONDS_PER_MINUTE);              // int(60)
	   	*/
	   	
    }
}
