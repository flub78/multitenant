<?php

/**
 * Test cases: CalendarEvent CRUD
 *
 */
namespace tests\Feature\Api;

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
	
	/**
	 * 
	 */
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
		
		// Without page parameter the URL returns a collection
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar');
		$response->assertStatus(200);
		
		$json = $response->json();
		// var_dump($json);
		$this->assertEquals(2, count($json['data']));
		$this->assertEquals('event 1', $json['data'][0]['title']);
	}
	
	/**
	 * 
	 */
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
	
	/**
	 * 
	 */
	public function test_calendar_event_store() {
		
		$initial_count = CalendarEvent::count ();
		
		//prepare an element
		$title = "Event $initial_count";
		$description = "Description $initial_count";
		$start = "07-31-2021";
		$elt = ['title' => $title, 'description' => $description, 'start' => $start, 'start_time' => '10:00',
				'allDay' => 0, 'end' => $start, 'end_time' => '12:00'
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
		$search = ['title' => $title, 'description' => $description];
		
		$event = CalendarEvent::where($search)->first();
		$this->assertNotNull($event);
		$this->assertEquals($event->allDay, 1);
	}
	
	/**
	 * 
	 */
	public function test_calendar_event_store_incorrect_value() {		
		$initial_count = CalendarEvent::count ();
						
		$title = "Event $initial_count";
		$description = "description $initial_count";
		$start = "start";
		$elt = ['title' => $title, 'description' => $description, 'start' => $start];
		
		$response = $this->postJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar', $elt);
		$json = $response->json();
		$this->assertEquals('The given data was invalid.', $json['message']);
		$this->assertEquals('The start does not match the format m-d-Y.', $json['errors']['start'][0]);
		
		// Check that nothing has been created
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $initial_count, $new_count, "event created, actual=$new_count, expected=$initial_count" );
	}

	/**
	 * 
	 */
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
	
	/**
	 * 
	 */
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
	
	/**
	 * 
	 */
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

	/**
	 * 
	 */
	public function test_calendar_event_pagination() {
		$this->be ( $this->user );
		
		$date = Carbon::now();
		for ($i = 0; $i < 100; $i++) {
			$date->add(2, 'hour');
			
			$evt = CalendarEvent::factory ()->create ( [
				'title' => "event_$i",
				'start' => '2021-06-30 12:00:00',
				'end' => $date->toDateTimeString(),
				'allDay' => ($i % 2 == 0) ? 0 :1
			] ); // UTC
		}
		
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// with a page parameter the API returns the collection in the data field
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_0', $json['data'][0]['title']);
		
		//echo "last_page_url = " . $json['last_page_url'] . "\n";
		$response = $this->getJson($json['last_page_url'] . '&per_page=20');
		$json = $response->json();
		// var_dump($json);
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_80', $json['data'][0]['title']);
	}
	
	public function test_bad_page_number() {
		$this->be ( $this->user );
		
		$date = Carbon::now();
		for ($i = 0; $i < 100; $i++) {
			$date->add(2, 'hour');
			
			$evt = CalendarEvent::factory ()->create ( [
					'title' => "event_$i",
					'start' => '2021-06-30 12:00:00',
					'end' => $date->toDateTimeString(),
					'allDay' => ($i % 2 == 0) ? 0 :1
			] ); // UTC
		}
		
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?per_page=20&page=120');
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(0, count($json['data'])); // just returns no data
		// var_dump($json);
	}
	
	
	public function test_calendar_event_sorting() {
		$this->be ( $this->user );
		
		// generate the test data set
		// 
		$date = Carbon::now();
		for ($i = 1; $i < 101; $i++) {			
			CalendarEvent::factory ()->create ( [
					'title' => "event_$i",
					'start' => $date->add(- $i, 'hour')->toDateTimeString(),
					'end' => $date->add($i, 'day')->toDateTimeString(),
					'allDay' => ($i % 2 == 0) ? 0 :1
			] ); // UTC
			$date->add(2, 'hour');
		}
		
		// Call a page
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// First call without sorting
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_1', $json['data'][0]['title']);  // regular order
		
		// Sorting on start (reverse order)
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?sort=-start');
		$json = $response->json();
		$this->assertEquals('event_100', $json['data'][0]['title']); // reverse order
		// var_dump($json);
	}
	
	public function test_calendar_event_sorting_on_multiple_columns() {
		$this->be ( $this->user );
		
		// generate the test data set
		//
		$date = Carbon::now();
		for ($i = 1; $i < 101; $i++) {
			CalendarEvent::factory ()->create ( [
					'title' => "event_$i",
					'start' => $date->add(- $i, 'hour')->toDateTimeString(),
					'end' => $date->add($i, 'day')->toDateTimeString(),
					'allDay' => ($i % 2 == 0) ? 0 :1
			] ); // UTC
			$date->add(2, 'hour');
		}
		
		// First page, non sorted
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// First call without sorting
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_1', $json['data'][0]['title']);  // regular order
		
		// Sorting on multiple columns
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?sort=allDay,-start');
		$json = $response->json();
		$this->assertEquals('event_100', $json['data'][0]['title']); // reverse order
		$this->assertEquals('event_98', $json['data'][1]['title']); // reverse order
		$this->assertEquals('event_96', $json['data'][2]['title']); // reverse order
		
		$this->assertEquals(0, $json['data'][0]['allDay']);
		$this->assertEquals(0, $json['data'][1]['allDay']);
		$this->assertEquals(0, $json['data'][2]['allDay']);
		$this->assertEquals(0, $json['data'][48]['allDay']);
		$this->assertEquals(0, $json['data'][49]['allDay']);
		$this->assertEquals(1, $json['data'][50]['allDay']);
		$this->assertEquals(1, $json['data'][51]['allDay']);
		$this->assertEquals(1, $json['data'][99]['allDay']);
		
		// var_dump($json);
	}

	public function test_sorting_on_bad_column_name() {
		$this->be ( $this->user );
		
		// generate the test data set
		//
		$date = Carbon::now();
		for ($i = 1; $i < 101; $i++) {
			CalendarEvent::factory ()->create ( [
					'title' => "event_$i",
					'start' => $date->add(- $i, 'hour')->toDateTimeString(),
					'end' => $date->add($i, 'day')->toDateTimeString(),
					'allDay' => ($i % 2 == 0) ? 0 :1
			] ); // UTC
			$date->add(2, 'hour');
		}
				
		// Sorting on multiple columns
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?sort=allDate,-startTime');
		$json = $response->json();
		$this->assertEquals("Illuminate\Database\QueryException", $json['exception']);
		$this->assertStringContainsString("Unknown column 'allDate'", $json['message']);
		
		// var_dump($json);
	}

	public function test_filtering() {
		$this->be ( $this->user );
		
		// generate the test data set
		//
		$date = Carbon::now();
		$start = Carbon::now();
		$end = Carbon::now();
		for ($i = 1; $i < 101; $i++) {
			CalendarEvent::factory ()->create ( [
					'title' => "event_$i",
					'start' => $start->toDateTimeString(),
					'end' => $end->toDateTimeString(),
					'allDay' => ($i % 2 == 0) ? 0 :1
			] ); // UTCs
			$start->sub(2, 'hour');
			$end->add(2, 'hour');
		}
		
		
		// Filtering on multiple columns
		$response = $this->getJson('http://' . tenant('id'). '.tenants.com/api/' . 'calendar?filter=allDay:1');
		$json = $response->json();
		$this->assertEquals(50, count($json['data']));
		
		// Filtering on multiple columns
		$limit = $date->sub(10, 'hour');
		$after =  htmlspecialchars(',start:>' . $limit->toDateTimeString());
		$response = $this->getJson('http://' . tenant('id') . 
				'.tenants.com/api/' . 'calendar?filter=allDay:1' . $after);
		$json = $response->json();
		$this->assertEquals(3, count($json['data']));

		$after =  htmlspecialchars(',start:>=' . $limit->toDateTimeString());
		$url = 'http://' . tenant('id') . '.tenants.com/api/' . 'calendar?filter=allDay:1' . $after;
		$response = $this->getJson($url);
		$json = $response->json();
		$this->assertEquals(3, count($json['data']));
		
		// var_dump($json);
	}
	
}
