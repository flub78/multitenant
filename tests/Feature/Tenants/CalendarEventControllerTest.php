<?php

/**
 * Test cases: CalendarEvent CRUD
 *
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\CalendarEvent;

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
				[__('calendar.add'), __('calendar.groupId'), __('calendar.event_title'), __('calendar.allday')]);	
	}

	public function test_calendar_event_fullcalendar() {
		$this->get_tenant_url($this->user, 'calendar/fullcalendar',
				[__('calendar.title')]);
	}
	
	public function test_calendar_event_create() {
		$this->get_tenant_url($this->user, 'calendar/create', ['Add Event']);		
	}

	public function test_calendar_json() {
		$this->be ( $this->user );
			
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/json' ;
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		// $response->dump();
		// $response->dumpHeaders();
		$response->assertSessionHasNoErrors();
	}

	public function test_calendar_event_store() {
		
		$initial_count = CalendarEvent::count ();
		
		//prepare an element
		$title = "Event $initial_count";
		$groupId = "GroupId $initial_count";
		$start = "07-31-2021";
		$elt = ['title' => $title, 'groupId' => $groupId, 'start' => $start, 'start_time' => '10:00',
				'allDay' => 1
		];
				
		// call the post method to create it
		$this->post_tenant_url($this->user, 'calendar', $elt);
		
		// check that an element has been created
		$new_count = CalendarEvent::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "event created, actual=$new_count, expected=$expected" );
		
		// and it can be retrieved
		$search = ['title' => $title, 'groupId' => $groupId];
		
		$event = CalendarEvent::where($search)->first();
		$this->assertNotNull($event);
		$this->assertEquals($event->allDay, 1);
	}
	
	public function test_calendar_event_store_incorrect_value() {		
		$initial_count = CalendarEvent::count ();
						
		$title = "Event $initial_count";
		$groupId = "GroupId $initial_count";
		$start = "start";
		$elt = ['title' => $title, 'groupId' => $groupId, 'start' => $start];
		
		$this->post_tenant_url( $this->user, 'calendar', $elt, $errors_expected = true);
		
		// Check that nothing has been created
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $initial_count, $new_count, "event created, actual=$new_count, expected=$initial_count" );
	}

	public function test_delete() {
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		$initial_count = CalendarEvent::count ();
		
		$this->delete_tenant_url($this->user, 'calendar/' . $id);
				
		$new_count = CalendarEvent::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}

	public function test_edit() {
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		
		$this->get_tenant_url($this->user, 'calendar/' . $id . '/edit', [__('calendar.edit')]);		
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
	
}
