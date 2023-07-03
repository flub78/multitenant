<?php

namespace tests\Unit;

use Tests\TenantTestCase;
use App\Helpers\Config;
use App;
use App\Models\Tenants\CalendarEvent;
use Carbon\Carbon;
use Carbon\CarbonInterval;

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
		if ($date1 == "" && $date2 == "")
			return true;

		$d1 = Carbon::createFromFormat ( 'Y-m-d H:i:s', $date1 );
		$d2 = Carbon::createFromFormat ( 'Y-m-d H:i:s', $date2 );

		$this->assertTrue ( $d1->EqualTo ( $d2 ), $d1 . " == " . $d2 );
	}

	/**
	 * Test Calendar Event accessors
	 */
	public function test_accessor() {
		$event = CalendarEvent::factory ()->create ( [ 
				'start' => '2021-06-30 12:00:00'
		] ); // UTC

		Config::set ( 'app.timezone', 'Europe/Paris' );
		App::setLocale ( 'fr' );
		$tz = Config::config ( 'app.timezone' );

		$this->assertNotNull ( $event );

		$event = CalendarEvent::factory ()->create ( [ 
				'start' => '2021-06-30 12:00:00',
				'end' => '2021-06-30 12:35:00'
		] ); // UTC

	}

	/**
	 * Test element creation, read, update and delete
	 * Given the database server is on
	 * And the schema exists in database
	 * When creating an element
	 * Then it is stored in database, it can be read, updated and deleted
	 */
	public function testCRUD() {
		$initial_count = CalendarEvent::count ();

		// Create
		$event = CalendarEvent::factory ()->make ( [ 
				'start' => '2021-06-30 12:00:00'
		] );
		$event->save ();

		// and a second
		CalendarEvent::factory ()->make ()->save ();

		$this->assertTrue ( CalendarEvent::count () == $initial_count + 2, "Two new elements in the table" );
		$this->assertDatabaseCount ( 'calendar_events', $initial_count + 2 );

		# Read
		$stored = CalendarEvent::where ( 'id', $event->id )->first ();

		$this->assertEquals ( $event->title, $stored->title );
		$this->assertEqualDates ( $event->start, $stored->start );
		$this->assertEqualDates ( $event->end, $stored->end );

		// Update
		$new_title = "updated title";
		$new_backgroundColor = "red";
		$stored->title = $new_title;
		$stored->backgroundColor = $new_backgroundColor;

		$stored->save ();

		$back = CalendarEvent::where ( 'id', $event->id )->first ();
		$this->assertEquals ( $back->title, $new_title, "After update" );
		$this->assertEquals ( $back->backgroundColor, $new_backgroundColor, "Updated color" );
		$this->assertDatabaseHas ( 'calendar_events', [ 
				'title' => $new_title
		] );

		// Delete
		$stored->delete ();
		$this->assertModelMissing ( $stored );
		$this->assertTrue ( CalendarEvent::count ()  == $initial_count + 1, "One less elements in the table" );
		$this->assertDatabaseMissing ( 'calendar_events', [ 
				'title' => $new_title
		] );
	}

	/**
	 * Test delete
	 * Given the database server is on
	 * And the schema exists in database
	 * When deleting a non exiting element
	 * Then nothing change in database
	 */
	public function test_deleting_non_existing_element() {
		$initial_count = CalendarEvent::count ();

		$event = CalendarEvent::factory ()->make ();
		$event->id = 999999999;
		$event->delete ();

		$this->assertTrue (CalendarEvent::count () == $initial_count, "No changes in database" );
	}

}
