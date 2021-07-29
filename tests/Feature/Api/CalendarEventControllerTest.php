<?php

/**
 * Test cases: CalendarEvent CRUD
 *
 */
namespace tests\Feature\Api;

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
	
	public function test_calendar_event_index_json() {
		
		$this->be ( $this->user );
		
		$evt1 = CalendarEvent::factory ()->create ( [
				'start' => '2021-06-30 12:00:00',
				'end' => '2021-06-30 12:35:00',
				'allDay' => 0
		] ); // UTC
		$evt2 = CalendarEvent::factory ()->create ( [
				'start' => '2021-06-30 13:30:00',
				'allDay' => 1
		] ); // UTC
		
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar');
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(2, count($json));
		$this->assertEquals('event 1', $json[0]['title']);
		
		// var_dump($json);				
	}
	
	public function test_show() {
		$this->be ( $this->user );
		
		$evt1 = CalendarEvent::factory ()->create ( [
				'start' => '2021-06-30 12:00:00',
				'end' => '2021-06-30 12:35:00',
				'allDay' => 0
		] ); // UTC
		$evt2 = CalendarEvent::factory ()->create ( [
				'start' => '2021-06-30 13:30:00',
				'allDay' => 1
		] ); // UTC
		
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar/1');
		$response->assertStatus(200);
		$json = $response->json();
		$this->assertEquals('event 1', $json['title']);
		//var_dump($json);
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
		$response = $this->postJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar', $elt);
		// $response->dump();
		$response->assertStatus(201);
		$json = $response->json();
		
		// by default the store method returns the created element
		$this->assertEquals('Event 0', $json['title']);
		
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
		
		$response = $this->postJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar', $elt);
		$json = $response->json();
		$this->assertEquals('The given data was invalid.', $json['message']);
		$this->assertEquals('The start does not match the format m-d-Y.', $json['errors']['start'][0]);
		
		// Check that nothing has been created
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $initial_count, $new_count, "event created, actual=$new_count, expected=$initial_count" );
	}

	public function test_delete() {
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		$initial_count = CalendarEvent::count ();
		
		$response = $this->deleteJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertEquals($json, 1);
		
		$new_count = CalendarEvent::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}
	
	public function test_delete_inexisting_elt() {
		$this->be ( $this->user );
		
		$id = "123456789";
		$initial_count = CalendarEvent::count ();
		
		$response = $this->deleteJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertTrue(strpos($json['message'], "No query results for model") >= 0);
		$this->assertTrue(strpos($json['message'], $id) >= 0);
		
		$new_count = CalendarEvent::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "Nothing deleted, actual=$new_count, expected=$expected" );
	}
	
	public function test_update() {
		
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
						
		$response = $this->patchJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar/' . $id, $elt);
		$this->assertEquals(1, $response->json());		

		$stored = CalendarEvent::findOrFail($id);
		$this->assertEquals($new_title, $stored->title);
        $this->assertEquals("2021-06-24 06:30:00", $stored->start);
        $this->assertEquals($stored->allDay, 0); 
        $this->assertEquals(3600 * 1.25, $stored->durationInSeconds());
        
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $new_count, $initial_count, "Count does not change on update, actual=$initial_count, expected=$new_count" );
	}
	
}
