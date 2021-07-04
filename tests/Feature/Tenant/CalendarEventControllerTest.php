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
		$this->be ( $this->user );
				
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar' ;		
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText(__('calendar.add'));
		$response->assertSeeText(__('calendar.groupId'));
		$response->assertSeeText(__('calendar.event_title'));
		$response->assertSeeText(__('calendar.allday'));	
	}

	public function test_calendar_event_fullcalendar() {
		$this->be ( $this->user );
		
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/fullcalendar' ;
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText(__('calendar.title'));
	}
	
	public function test_calendar_event_create() {
		$this->be ( $this->user );
				
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/create' ;
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText('Add Event');
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
		$this->be ( $this->user );
		
		$count = CalendarEvent::count ();
		
		$this->withoutMiddleware();
		
		$title = "Event $count";
		$groupId = "GroupId $count";
		$start = "07-31-2021";
		$elt = ['title' => $title, 'groupId' => $groupId, 'start' => $start, 'start_time' => '10:00',
				'allDay' => 1
		];
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar' ;
		$response = $this->post ( $url, $elt);
		$response->assertStatus ( 302 );
		
		$response->assertSessionHasNoErrors();
		
		$new_count = CalendarEvent::count ();
		$expected = $count + 1;
		$this->assertEquals ( $expected, $new_count, "event created, actual=$new_count, expected=$expected" );
		
		$search = ['title' => $title, 'groupId' => $groupId];
		
		$event = CalendarEvent::where($search)->first();
		$this->assertNotNull($event);
		$this->assertEquals($event->allDay, 1);
	}
	
	public function test_calendar_event_store_incorrect_value() {
		$this->be ( $this->user );
		
		$count = CalendarEvent::count ();
		
		$this->withoutMiddleware();
		
		$title = "Event $count";
		$groupId = "GroupId $count";
		$start = "start";
		$elt = ['title' => $title, 'groupId' => $groupId, 'start' => $start];
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar' ;
		$response = $this->post ( $url, $elt);
		$response->assertStatus ( 302 );
		
		$response->assertSessionHasErrors();
		
		$new_count = CalendarEvent::count ();
		$expected = $count;
		$this->assertEquals ( $expected, $new_count, "event created, actual=$new_count, expected=$expected" );
	}

	public function test_delete() {
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();

		$count = CalendarEvent::count ();
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/' . $id;
		
		$response = $this->delete ( $url);
		$response->assertStatus ( 302 );
		
		$new_count = CalendarEvent::count ();
		$expected = $count - 1;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}

	public function test_edit() {
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
				
		$url = 'http://' . tenant('id'). '.tenants.com/calendar/' . $id . '/edit';
				
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText(__('calendar.edit'));
	}
	
	public function test_update() {
		// TODO test allDay attributes
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		
		$event = CalendarEvent::find($id);
		$count = CalendarEvent::count ();
		
		$this->assertEquals($event->allDay, 1); // by default
		
		$this->withoutMiddleware();

		$url = 'http://' . tenant('id'). '.tenants.com/calendar/' . $id;
		
		$new_title = "new title";
		$new_start = '06-24-2021';
		$elt = ["id" => $event->id, "title" => $new_title, 'start' => $new_start, 'start_time' => '06:30', 
				'allDay' => false, '_token' => csrf_token()];
				
		$response = $this->patch ( $url, $elt);
		// $response->dumpSession();
		
		$response->assertStatus ( 302);
		
		$stored = CalendarEvent::findOrFail($id);
		$this->assertEquals($new_title, $stored->title);
        $this->assertEquals("2021-06-24 06:30:00", $stored->start);
        $this->assertEquals($stored->allDay, 0); 
        
		$expected = CalendarEvent::count ();
		$this->assertEquals ( $expected, $count, "Count does not change on update, actual=$count, expected=$expected" );
	}
	
}
