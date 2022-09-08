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
		
		$this->base_url = '/calendar_event';
	}

	function __destruct() {
		$this->user->delete ();
	}
	
	/**
	 * Test that base URL returns a json list of elements
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
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url);
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(2, count($json['data']));
		$this->assertEquals('event 1', $json['data'][0]['title']);
	}
	
	/**
	 * Get one element form the API
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
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/1');
		$response->assertStatus(200);
		$json = $response->json();
		$this->assertEquals('event 1', $json['title']);
	}
	
	/**
	 *
	 */
	public function test_fullcalendar() {
		$this->be ( $this->user );
		
		$evt1 = CalendarEvent::factory ()->create ( [
				'start' => '2021-06-30 12:00:00',
				'end' => '2021-06-30 12:35:00',
				'allDay' => 0,
				'textColor' => '#FF0000',
				'borderColor' => '#FFFF00',
				'backgroundColor' => '#FFFFFF'
				
		] ); // UTC
		$evt2 = CalendarEvent::factory ()->create ( [
				'start' => '2021-06-30 13:30:00',
				'allDay' => 1
		] ); // UTC
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/fullcalendar' .
				'?start=2021-06-01T00:00:00+01:00&end=2021-06-30T23:59:00+01:00');
		$response->assertStatus(200);
		$json = $response->json();
		//$this->assertEquals('event 1', $json['title']);
	}
	
	
	/**
	 * Create elements from the API
	 */
	public function test_calendar_event_store() {
		
		$initial_count = CalendarEvent::count ();
		
		//prepare an element
		$title = "Event $initial_count";
		$description = "Description $initial_count";
		$start = "2021-07-31 10:00";
		$end = "2021-07-31 12:00";
		$elt = ['title' => $title, 'description' => $description, 'start' => $start, 
				'allDay' => 0, 'end' => $end
		];
				
		// call the post method to create it
		$response = $this->postJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url , $elt);
		
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
	 * Test store error cases
	 */
	public function test_calendar_event_store_incorrect_value() {		
		$initial_count = CalendarEvent::count ();
						
		$title = "Event $initial_count";
		$description = "description $initial_count";
		$start = "start";
		$elt = ['title' => $title, 'description' => $description, 'start' => $start];
		
		$response = $this->postJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url, $elt);
		$json = $response->json();
		$this->assertEquals('The given data was invalid.', $json['message']);
		$this->assertEquals('The start is not a valid date.', $json['errors']['start'][0]);
		
		// Check that nothing has been created
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $initial_count, $new_count, "event created, actual=$new_count, expected=$initial_count" );
	}

	/**
	 * Delete an element through the API
	 */
	public function test_delete() {
		$this->be ( $this->user );
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		$initial_count = CalendarEvent::count ();
		
		$response = $this->deleteJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertEquals($json, 1);
		
		$new_count = CalendarEvent::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}
	
	/**
	 * Test deleting a non existing element
	 */
	public function test_delete_inexisting_elt() {
		$this->be ( $this->user );
		
		$id = "123456789";
		$initial_count = CalendarEvent::count ();
		
		$response = $this->deleteJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertTrue(strpos($json['message'], "No query results for model") >= 0);
		$this->assertTrue(strpos($json['message'], $id) >= 0);
		
		$new_count = CalendarEvent::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "Nothing deleted, actual=$new_count, expected=$expected" );
	}
	
	/**
	 * Check that an element can be updated through the REST API
	 */
	public function test_update() {
		
		$event = CalendarEvent::factory()->make();
		$id = $event->save();
		
		$event = CalendarEvent::find($id);
		$initial_count = CalendarEvent::count ();
		
		$this->assertEquals($event->allDay, 1); // by default
		
		$new_title = "new title";
		$new_start = '2021-06-24 06:30';
		$new_end = '2021-06-24 07:45';
		$elt = ["id" => $event->id, "title" => $new_title,
				'start' => $new_start, 'end' => $new_end,
				'allDay' => false, '_token' => csrf_token()];
						
		$response = $this->patchJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id, $elt);
		$this->assertEquals(1, $response->json());		

		$stored = CalendarEvent::findOrFail($id);
		$this->assertEquals($new_title, $stored->title);
        $this->assertEquals("2021-06-24 06:30:00", $stored->start);
        $this->assertEquals($stored->allDay, 0); 
        
		$new_count = CalendarEvent::count ();
		$this->assertEquals ( $new_count, $initial_count, "Count does not change on update, actual=$initial_count, expected=$new_count" );
	}

	/**
	 * Test pagination
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
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// with a page parameter the API returns the collection in the data field
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_0', $json['data'][0]['title']);
		
		//echo "last_page_url = " . $json['last_page_url'] . "\n";
		$response = $this->getJson($json['last_page_url'] . '&per_page=20');
		$json = $response->json();
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_80', $json['data'][0]['title']);
	}
	
	/**
	 * Non existing page number
	 */
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
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=120');
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(0, count($json['data'])); // just returns no data
	}
	
	
	/**
	 * Test that pages are correctly sorted
	 */
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
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// First call without sorting
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_1', $json['data'][0]['title']);  // regular order
		
		// Sorting on start (reverse order)
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=-start');
		$json = $response->json();
		$this->assertEquals('event_100', $json['data'][0]['title']); // reverse order
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
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// First call without sorting
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_1', $json['data'][0]['title']);  // regular order
		
		// Sorting on multiple columns
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=allDay,-start');
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
	}

	/**
	 * Sorting on bad column name
	 */
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
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=allDate,-startTime');
		$json = $response->json();
		$this->assertEquals("Illuminate\Database\QueryException", $json['exception']);
		$this->assertStringContainsString("Unknown column 'allDate'", $json['message']);
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
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=allDay:1');
		$json = $response->json();
		$this->assertEquals(50, count($json['data']));
		
		// Filtering on multiple columns
		$limit = $date->sub(10, 'hour');
		$after =  htmlspecialchars(',start:>' . $limit->toDateTimeString());
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=allDay:1' . $after);
		$json = $response->json();
		$this->assertEquals(3, count($json['data']));

		$after =  htmlspecialchars(',start:>=' . $limit->toDateTimeString());
		$url = 'http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=allDay:1' . $after;
		$response = $this->getJson($url);
		$json = $response->json();
		$this->assertEquals(3, count($json['data']));
	}
	
}
