<?php

/**
 * Test cases: CalendarEvent CRUD
 *
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;

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
		$response->assertSeeText('New event');
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
	}

	public function test_calendar_event_store() {
		$this->be ( $this->user );
		
		$this->withoutMiddleware();
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar' ;
		$response = $this->post ( $url, []);
		$response->assertStatus ( 302 );
	}
	
	
}
