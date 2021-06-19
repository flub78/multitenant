<?php

/**
 * Test cases:
 *
 * Nominal: list backups, create a new one, checks that it exists and delete it.
 *
 * Error test case:
 * delete a non existing backup
 * restore a non existing backup
 *
 * attempt to create, restore or delete a backup as non admin
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Helpers\TenantHelper;

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
	 */
	public function ttest_main_calendar_view() {
		$this->be ( $this->user );
		
		// echo ("tenant = " . tenant('id'));
		
		
		$url = 'http://' . tenant('id'). '.tenants.com/calendar' ;
		
		echo "url=" . $url;
		
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		// $response->assertSeeText('calendar');
		
	}

	
}
