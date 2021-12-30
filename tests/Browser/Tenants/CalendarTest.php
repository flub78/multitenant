<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;
use Carbon\Carbon;


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
			->assertSee(__('calendar_event.add'))
			->assertSee(__('calendar_event.start_date'))
			->assertSee(__('calendar_event.start_time'))
			->assertSee(__('calendar_event.allday'))
			;
			
			$browser->storeSource('Tenants/calendar_list.html');
		} );		
	}

	public function test_calendar_create () {
		
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/calendar/create' )
			->assertSee('Multi')
			->assertSee('test')
			->assertSee(__('calendar_event.new'))
			->assertSee(__('calendar_event.start_date'))
			->assertSee(__('calendar_event.start_time'))
			->assertSee(__('calendar_event.allday'))
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
	
	public function test_fullcalendar_create () {
		
		
		$this->browse ( function (Browser $browser) {
			
			// display fullcalendar
			$browser->visit ( '/calendar/fullcalendar' )
			->assertSee('Multi')
			->assertSee('test');
			
			/** To click on today date
			 * <td class="fc-daygrid-day fc-day fc-day-wed fc-day-past" data-date="2021-12-22">
			 *     <div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner">
			 *         <div class="fc-daygrid-day-top">
			 *             <a class="fc-daygrid-day-number">22</a>
			 *         </div>
			 *         <div class="fc-daygrid-day-events">
			 *             <div class="fc-daygrid-day-bottom" style="margin-top: 24px;">
			 *             </div>
			 *             </div><div class="fc-daygrid-day-bg"></div></div></td>
			 */
			
		
			$today =  Carbon::now();
			$time = $today->toDateTimeString();
			
			$event_title = "event_" . str_shuffle("abcdefghijklmnopqrstuvwxyz");
			$date = $today->toDateString();
			$day = $today->day;
			$selector = "[data-date='$date']";
			
			/*
			echo "dateTime = " . $time . "\n";
			echo "date = $date\n";
			echo "day = $day\n";
			echo "title = " . $event_title . "\n";
			echo "selector = $selector\n";
			*/
			
			$browser->click($selector);
			$browser->assertSee (__('calendar_event.new'))			
			->assertSee(__('calendar_event.start_date'))
			->assertSee(__('calendar_event.start_time'))
			->assertSee(__('calendar_event.allday'));
			
			// check we are on the create view
			$browser->screenshot('Tenants/after_day_clicked');
			
			$browser->type ( 'title', $event_title)
			->type('start', $today->format('m-d-Y'))
			->check('allDay')
			->type('start_time', $today->format('H:i'))
			->press ( 'Add Event' )
			->assertDontSee('The start does not match the format m-d-Y')
			->assertSee($event_title);
			
			$browser->screenshot('Tenants/after_fullcalendar_create');
			
		} );
	}
	
	public function test_fullcalendar_click_event () {
			
		$this->browse ( function (Browser $browser) {
			
			$today =  Carbon::now();
			$time = $today->toDateTimeString();
			
			$event_title = "event_" . str_shuffle("abcdefghijklmnopqrstuvwxyz");
			$date = $today->toDateString();
			$day = $today->day;			

			 echo "dateTime = " . $time . "\n";
			 echo "date = $date\n";
			 echo "day = $day\n";
			 echo "title = " . $event_title . "\n";

			
			// create an event
			$browser->visit ( '/calendar/create' )
			->assertSee('Multi')
			->assertSee('test')
			->assertSee(__('calendar_event.new'))
			->assertSee(__('calendar_event.start_date'))
			->assertSee(__('calendar_event.start_time'))
			->assertSee(__('calendar_event.allday'))
			;
						
			$browser->type ( 'title', $event_title)
			->type('start', $today->format('m-d-Y'))
			->check('allDay')
			->type('start_time', $today->format('H:i'))
			->press ( 'Add Event' )
			->assertDontSee('The start does not match the format m-d-Y')
			->assertSee($event_title);
			
			// display fullcalendar
			$browser->visit ( '/calendar/fullcalendar' )
			->assertSee('Multi')
			->assertSee('test')
			->assertSee($event_title);
			
			$browser->screenshot('Tenants/fullcalendar_after_create');
			
			// click on the event
			$browser->clickLink($event_title);
			$browser->assertSee (__('calendar_event.edit'))
			->assertSee(__('calendar_event.start_date'))
			->assertSee(__('calendar_event.start_time'))
			->assertSee(__('calendar_event.allday'));
			
			$input_title = $browser->inputValue('title');
			$this->assertEquals($input_title, $event_title);
			
			
			/**
			 * To click on an event
			 * <div class="fc-daygrid-day-top">
			 *     <a class="fc-daygrid-day-number">21</a>
			 * </div>
			 * <div class="fc-daygrid-day-events">
			 *     <div class="fc-daygrid-event-harness fc-daygrid-event-harness-abs" style="top: 0px; left: 0px; right: -202.267px;">
			 *         <a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-past" style="border-color: rgb(238, 238, 238); background-color: rgb(204, 0, 0);" href="http://abbeville.tenants.com/calendar/3/edit">
			 *            <div class="fc-event-main" style="color: black;">
			 *                <div class="fc-event-main-frame">
			 *                    <div class="fc-event-title-container">
			 *                        <div class="fc-event-title fc-sticky">Test event</div>
			 *                    </div>
			 *                </div>
			 *            </div>
			 *            <div class="fc-event-resizer fc-event-resizer-end"></div>
			 *        </a></div><div class="fc-daygrid-day-bottom" style="margin-top: 24px;"></div></div><div class="fc-daygrid-day-bg"></div>
			 */
		} );
	}
}
