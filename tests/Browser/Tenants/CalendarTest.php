<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Dusk test for the Calendar feature 
 * @author frederic
 *
 */
class CalendarTest extends DuskTestCase {


	public function setUp(): void {
		parent::setUp ();
		$database = "tenanttest";
		
		/**		
		echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		echo "url=" . env('APP_URL') . "\n";
		echo "database=$database\n";
		echo "language=" . __('general.language') . "\n";		// English by default
		*/

		// Restore a test database
		$filename = storage_path () . '/app/tests/tenant_nominal.gz';
		$this->assertFileExists($filename, "tenant_nominal test backup found");
		BackupHelper::restore($filename, $database, false);		
	}

	public function tearDown(): void {
		parent::tearDown ();
	}


	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser);
			
			$browser->screenshot('Tenants/after_login');
		} );
	}

	public function test_fullcalendar () {
		
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/calendar/fullcalendar' )
			->assertSee('Multi')
			->assertSee('test');
		} );
			
	}
	
	public function test_calendar () {
		
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/calendar' )
			->assertSee('Multi')
			->assertSee('test')
			->assertSee(__('general.edit'))
			->assertSee(__('general.delete'))
			->assertSee(__('calendar.add'))
			->assertSee(__('calendar.start_date'))
			->assertSee(__('calendar.start_time'))
			->assertSee(__('calendar.allday'))
			;
			
			$browser->storeSource('Tenants/calendar_list.html');
		} );		
	}

	public function test_calendar_create () {
		
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/calendar/create' )
			->assertSee('Multi')
			->assertSee('test')
			->assertSee(__('calendar.new'))
			->assertSee(__('calendar.start_date'))
			->assertSee(__('calendar.start_time'))
			->assertSee(__('calendar.allday'))
			;
			
			$browser->storeSource('Tenants/calendar_create.html');
			
			$browser->type ( 'title', 'dentist')
			->type('start', '07-13-2021')
			->check('allDay')
			->type('start_time', '10:15')
			->press ( 'Add Event' )
			->assertDontSee('The start does not match the format m-d-Y');

			// sleep(10);
		} );
	}
	
}
