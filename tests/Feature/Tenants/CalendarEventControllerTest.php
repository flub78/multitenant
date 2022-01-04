<?php

/**
 * Test cases: CalendarEvent CRUD
 *
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\CalendarEvent;
use Carbon\Carbon;

class CalendarEventControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}

	
	public function test_calendar_event_list() {
		
		$this->get_tenant_url($this->user, 'calendar', 
				[__('calendar_event.add'), __('calendar_event.description'), __('calendar_event.event_title'), __('calendar_event.allday')]);	
	}

	public function test_calendar_event_fullcalendar() {
		$this->get_tenant_url($this->user, 'calendar/fullcalendar',
				[__('calendar_event.title')]);
	}
	
	public function test_calendar_event_create() {
		$this->get_tenant_url($this->user, 'calendar/create', ['Add Event']);		
	}
	
	public function test_calendar_event_create_with_start() {
		$this->get_tenant_url($this->user, 'calendar/create?action=fullcalendar&start=2022-01-05T11:00:00', ['Add Event']);
	}
	

	public function test_calendar_event_store() {
		
		$initial_count = CalendarEvent::count ();
		
		// minimal event
		//prepare an element
		$title = "Event $initial_count";
		$description = "description $initial_count";
		$start = "07-31-2021";
		$elt = ['title' => $title, 'description' => $description, 'start' => $start, 'start_time' => '10:00',
				'allDay' => 1, 'backgroundColor' => '#000000', 'textColor' => '#000000'
		];
				
		// call the post method to create it
		$this->post_tenant_url($this->user, 'calendar', ['created'], $elt);
		
		// check that an element has been created
		$new_count = CalendarEvent::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "event created, actual=$new_count, expected=$expected" );
		
		// and it can be retrieved		
		$event = CalendarEvent::where('title', $title)->first();
		/*
		var_dump($event);
  array(14) {
    'id' =>
    int(1)
    'title' =>
    string(7) "Event 0"
    'description' =>
    string(13) "description 0"
    'allDay' =>
    int(1)
    'start' =>
    string(19) "2021-07-31 10:00:00"
    'end' =>
    NULL
    'editable' =>
    int(1)
    'startEditable' =>
    int(1)
    'durationEditable' =>
    int(1)
    'backgroundColor' =>
    NULL
    'borderColor' =>
    NULL
    'textColor' =>
    NULL
    'created_at' =>
    string(19) "2021-12-14 14:51:15"
    'updated_at' =>
    string(19) "2021-12-14 14:51:15"
  }
		 */
		$this->assertNotNull($event);
		$this->assertEquals($event->allDay, 1);  
		$this->assertEquals($event->title, $title);
		$this->assertEquals($event->description, $description);
		$this->assertEquals($event->description, $description);
		$this->assertEquals('2021-07-31 10:00:00', $event->start);
		
		/* #################################################################################### */
		
		// full event
		//prepare an element
		$title = "Event $new_count";
		$description = "description $new_count";
		$start = "08-01-2021";
		$end = "08-02-2021";
		$elt = ['title' => $title, 
				'description' => $description, 
				'start' => $start, 
				'start_time' => '10:00',
				'allDay' => false,
				'end' => $end,
				'end_time' => '12:00',
		];
		
		// call the post method to create it
		$this->post_tenant_url($this->user, 'calendar', ['created'], $elt);
		
		// check that an element has been created
		$new_count = CalendarEvent::count ();
		$expected = $initial_count + 2;
		$this->assertEquals ( $expected, $new_count, "second event created, actual=$new_count, expected=$expected" );
		
		// and it can be retrieved
		
		$event2 = CalendarEvent::where('title', $title)->first();
		$this->assertNotNull($event2);
		$this->assertEquals($event2->allDay, 0);
		$this->assertEquals($event2->title, $title);
		$this->assertEquals($event2->description, $description);
		$this->assertEquals('2021-08-01 10:00:00', $event2->start);
		$this->assertEquals('2021-08-02 12:00:00', $event2->end);	
	}
	
	public function test_calendar_event_store_incorrect_value() {		
		$initial_count = CalendarEvent::count ();
						
		$title = "Event $initial_count";
		$description = "description $initial_count";
		$start = "start";
		$elt = ['title' => $title, 'description' => $description, 'start' => $start];
		
		$this->post_tenant_url( $this->user, 'calendar', [], $elt, $errors_expected = true);
		
		// Check that nothing has been created
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $initial_count, $new_count, "event created, actual=$new_count, expected=$initial_count" );
	}

	public function test_delete() {
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		$initial_count = CalendarEvent::count ();
		
		$this->delete_tenant_url($this->user, 'calendar/' . $id, ['deleted']);
				
		$new_count = CalendarEvent::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}

	public function test_edit() {
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		
		$this->get_tenant_url($this->user, 'calendar/' . $id . '/edit', [__('calendar_event.edit'), __('general.delete')]);		
	}
	
	public function test_update() {
		// TODO test allDay attributes
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		
		$event = CalendarEvent::find($id);
		$initial_count = CalendarEvent::count ();
		
		$this->assertEquals($event->allDay, 1); // by default
		
		$new_title = "new title";
		$new_start = '06-24-2021';
		$elt = ["id" => $event->id, "title" => $new_title,
				'start' => $new_start, 'end' => $new_start,
				'start_time' => '06:30', 'end_time' => '07:45', 
				'allDay' => false, '_token' => csrf_token()];
						
		$this->patch_tenant_url( $this->user, 'calendar/' . $id, $elt);
		
		$stored = CalendarEvent::findOrFail($id);
		$this->assertEquals($new_title, $stored->title);
        $this->assertEquals("2021-06-24 06:30:00", $stored->start);
        $this->assertEquals($stored->allDay, 0); 
        $this->assertEquals(3600 * 1.25, $stored->durationInSeconds());
        
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $new_count, $initial_count, "Count does not change on update, actual=$initial_count, expected=$new_count" );
	}
	
	/*
	 * Fullcalendar is a javascript module, events are not available in the HTML page before the DOM
	 * is extended by the javascript. It cannot be tested with phpunit (use Dusk instead).
	 */	
	public function test_calendar_event_create_parameters() {
		$this->get_tenant_url($this->user, 'calendar/create', ['Add Event']);
	}
	
	public function test_fullcalendar_dragged() {
		$this->be ( $this->user );
		
		// Create an event
		$now =  Carbon::now();
		$time = $now->toDateTimeString();
		$event_title = "event_" . str_shuffle("abcdefghijklmnopqrstuvwxyz");
		
		$event = CalendarEvent::factory ()->make ( [
				'start' => $time,
				'end' => $now->addHours(2)->toDateTimeString(),
				'description' => $event_title
		] );
		$event->save ();
		
		$id = $event->id;
				
		// Missing ID
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/dragged' ;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Missing calendar event ID', 'code' => 1]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		
		// Unknown ID
		$start='2022-01-07';
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/dragged?id=1000000000&start=' . $start ;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Unknown calendar event ID', 'code' => 4]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		
		// Missing start
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/dragged?id=1000000000' ;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Missing calendar event start', 'code' => 2]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		
		// Incorrect start date
		/*
		 * not able to trigger an error with parse ....

		$start='15/25/2022';
		$url = 'http://' . tenant('id'). ".tenants.com/calendar/dragged?id=$id&start=$start" ;
		echo "url=$url\n";
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Incorrect event start format', 'code' => 3]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		*/
		
		// Correct answer
		$url = 'http://' . tenant('id'). ".tenants.com/calendar/dragged?id=$id&start=" . $start;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertExactJson(['status' => 'OK']);
		$response->assertSessionHasNoErrors();
		$this->assertArrayNotHasKey('error', $response);
		
		// $response->dumpHeaders();
		// $response->dump();
	}
	
	public function test_fullcalendar_resized() {		
		
		$this->be ( $this->user );
		
		// Create an event
		$now =  Carbon::now();
		$in_one_hour = $now->addHours(1);
		$in_two_hours = $now->addHours(2);
		$new_end = $in_two_hours->toDateTimeString();
		
		$time = $now->toDateTimeString();
		$event_title = "event_" . str_shuffle("abcdefghijklmnopqrstuvwxyz");
		
		$event = CalendarEvent::factory ()->make ( [
				'start' => $time,
				'end' => $in_one_hour->toDateTimeString(),
				'description' => $event_title
		] );
		$event->save ();
		
		$id = $event->id;
		
		// Missing ID
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/resized' ;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Missing calendar event ID', 'code' => 1]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		
		// Unknown ID
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/resized?id=1000000000&end=' . $new_end ;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Unknown calendar event ID', 'code' => 4]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		
		// Missing end
		$url = 'http://' . tenant('id'). ".tenants.com/calendar/resized?id=$id";
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertJson(['error' => ['message' => 'Missing calendar event end', 'code' => 2]]);
		$this->assertNotNull($response['error']);
		$response->assertSessionHasNoErrors();
		
		// Correct answer
		$url = 'http://' . tenant('id'). ".tenants.com/calendar/resized?id=$id&end=" . $new_end;
		$response = $this->getJson($url);
		$response->assertStatus ( 200 );
		$response->assertExactJson(['status' => 'OK']);
		$response->assertSessionHasNoErrors();
		$this->assertArrayNotHasKey('error', $response);
		
		// $response->dumpHeaders();
		// $response->dump();
	}
	
}
