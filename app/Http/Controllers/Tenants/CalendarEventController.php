<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\Tenants\CalendarEvent;
use App\Http\Requests\Tenants\CalendarEventRequest;
use Illuminate\Http\Request;
use App\Helpers\DateFormat;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Log;
use App\Helpers\Config;
use BaconQrCode\Common\FormatInformation;


/**
 * Calendar Events Controllers
 *
 * Calendar events are items contained in a Calendar. They have a date, start time, end time, etc.
 * To some extend this class is inspired form Google and others calendar.
 *
 * Calendar is a tenant only feature. Central application is really limited to the tenant management.
 *
 * @author frederic
 * @reviewed 2022-01-08
 *        
 */
class CalendarEventController extends Controller {

	// name of the table
	private $base_view = 'tenants.calendar_event.';
	private $base_url = 'calendar_event';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$events = CalendarEvent::all ();
		return view ( $this->base_view . 'index', compact ( 'events' ) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function fullcalendar() {
		Log::Debug("CalendarEventController.fullcalendar");
		
		$events = CalendarEvent::all ();
		return view ( $this->base_view . 'calendar', compact ( 'events' ) );
	}


	/**
	 * Show the form for creating a new resource.
	 * 
	 * @param start (optional) a value to prefill the Form
	 * @param action (optional) set to fullcalendar when called from fullcalendar
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {
		
		Log::debug("CalendarEventController.create, action=" . $request->get ('action') .
				", start=" . $request->get ('start'));
		
		$data = ['action' => $request->get ('action')];
		
		if ($request->get ('start')) {
			
			$start = explode(' ', $request->get ('start'))[0];
			$cstart = Carbon::parse($start);
			$data['start_date'] = $cstart->format(__('general.date_format'));
			$start_time = $cstart->format(__('general.time_format'));
			if (strlen($start) > 12) {
				// a time was specified.
				$data['start_time'] = $start_time;
			} else {
				$data['start_time'] = "";
			}
		} else {
			$data['start_date'] = "";
			$data['start_time'] = "";
		}
		$data['defaultBackgroundColor'] = "#00FFFF";
		$data['defaultTextColor'] = "#808080";
		
		return view ( $this->base_view . 'create', $data );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CalendarEventRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CalendarEventRequest $request) {
		$validatedData = $request->validated ();
		
		/**
array (size=9)
  'title' => string 'Titre' (length=5)
  'description' => string 'Description' (length=11)
  'start_date' => string '14/12/2021' (length=10)
  'start_time' => string '10:20' (length=5)
  'end' => string '15/12/2021' (length=10)
  'end_time' => string '10:30' (length=5)
  'allDay' => string '1' (length=1)
  'backgroundColor' => string '#990000' (length=7)
  'textColor' => string '#38761d' (length=7)
*/

		Log::Debug("CalendarEventController.stored: validated=" . var_export($validatedData, true));
		
		$validatedData ['allDay'] = $request->has ( 'allDay' ) && $request->allDay;
		
		if (array_key_exists ( 'start_date', $validatedData ) && $validatedData ['start_date']) {
			if (! array_key_exists ( 'start_time', $validatedData )) {
				$validatedData ['start_time'] = '';
			}
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start_date'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end_date', $validatedData ) && $validatedData ['end_date']) {
			if (! array_key_exists ( 'end_time', $validatedData )) {
				$validatedData ['end_time'] = '';
			}
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end_date'], $validatedData ['end_time'] );
		}
		if (!array_key_exists ( 'backgroundColor', $validatedData )) {
			$validatedData ['backgroundColor'] = '#FFFFFF';
		}
		if (!array_key_exists ( 'textColor', $validatedData )) {
			$validatedData ['textColor'] = '#000000';
		}

		unset ( $validatedData ['start_date'] );
		unset ( $validatedData ['end_date'] );
		unset ( $validatedData ['start_time'] );
		unset ( $validatedData ['end_time'] );
		
		CalendarEvent::create ( $validatedData );

		return redirect ( $this->base_url )->with ( 'success', __ ( 'general.creation_success', [ 
				'elt' => $validatedData ['title']
		] ) );
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $id of
	 *        	the calendar event to edit
	 *        	the event
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function edit($id) {
		$calendarEvent = CalendarEvent::findOrFail ( $id );
		return view ( $this->base_view . 'edit' )->with ( 'calendar_event', $calendarEvent );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param CalendarEventRequest $request
	 * @param
	 *        	id of the event
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function update(CalendarEventRequest $request, $id) {
		$validatedData = $request->validated ();

		Log::Debug("CalendarEventController.update: id=$id, validated=" . var_export($validatedData, true));
		
		if (array_key_exists ( 'start_date', $validatedData ) && $validatedData ['start_date']) {
			if (! array_key_exists ( 'start_time', $validatedData )) {
				$validatedData ['start_time'] = '';
			}
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start_date'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end_date', $validatedData ) && $validatedData ['end_date']) {
			if (! array_key_exists ( 'end_time', $validatedData )) {
				$validatedData ['end_time'] = '';
			}
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end_date'], $validatedData ['end_time'] );
		}
		$validatedData ['allDay'] = $request->has ( 'allDay' ) && $request->allDay;

		unset ( $validatedData ['start_date'] );
		unset ( $validatedData ['end_date'] );
		unset ( $validatedData ['start_time'] );
		unset ( $validatedData ['end_time'] );

		CalendarEvent::whereId ( $id )->update ( $validatedData );

		return redirect ( $this->base_url )->with ( 'success', __ ( 'general.modification_success', [ 
				'elt' => $validatedData ['title']
		] ) );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $id of
	 *        	the event
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function destroy(string $id) {
		$calendarEvent = CalendarEvent::findOrFail ( $id );
		$title = $calendarEvent->title;
		$calendarEvent->delete ();

		return redirect ( $this->base_url )->with ( 'success', __('general.deletion_success', ['elt' => $title]));		
	}

	/**
	 * Called by fullcalendar when an event is dragged
	 * 
	 * @param Request $request
	 * @return json status => 'OK' or ['error' => ['message' => 'error message', 'code' => 1234]];
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function dragged (Request $request) {
		
		$id = $request->get ('id');
		$title = $request->get ('title');
		$new_start = $request->get ('start');
		$end = $request->get ('end');
		$allDay = $request->get ('allDay');
		
		Log::Debug("Event $id, title=$title, has been draggged to $new_start end=$end, allDay=$allDay");
		/*
		 * Event 12, title=Docteur, has been draggged to 2022-01-05T09:00:00Z end=2022-01-05T13:00:00Z, allDay=false 
		 * 
		 * fullcalendar sends date in local time
		 */
		
		if (! $id) {
			$output = ['error' => ['message' => 'Missing calendar event ID', 'code' => 1]];
			Log::Debug('Missing calendar event ID');
			return response()->json($output);
		}
		
		$start_datetime = null;
		if (! $new_start) {
			$output = ['error' => ['message' => 'Missing calendar event start', 'code' => 2]];
			Log::Debug('Missing calendar event start');
			
			return response()->json($output);
			
		} else {
			try {
				$exploded = explode(' ', $new_start);
				$start_datetime = Carbon::parse($exploded[0], Config::config('app.timezone'));		
			} catch ( Exception $e ) {
				$output = ['error' => ['message' => 'Incorrect event start format', 'code' => 3]];
				Log::Debug('Incorrect event start format: ' . $new_start);
				return response()->json($output);
			}
		}
		
		// Fetch the event
		$event = CalendarEvent::find ($id);	
		
		if (! $event) {
			$output = ['error' => ['message' => 'Unknown calendar event ID', 'code' => 4]];
			Log::Debug('Unknown calendar event ID');
			return response()->json($output);
		}
		
		// compute the difference between initial and last position
		$initial_start = Carbon::parse($event['start']);
		$initial_start->tz(Config::config('app.timezone'));
		
		$delta = $initial_start->diff($start_datetime);
		
		Log::debug("initial start = " . $initial_start->format('Y-m-d H:i e'));
		Log::debug("new start     = " . $start_datetime->format('Y-m-d H:i e'));
		Log::debug('delta = ' . $delta->format("%a days %H:%I:%S"));
		Log::debug('diffInHours = ' . $initial_start->diffInHours($start_datetime) );
		
		// apply the delta to start dateTime
		$start_datetime->setTimezone('UTC');
		
		$data = [];
		$data ['start'] = $start_datetime->format("Y-m-d H:i");
		$data ['allDay'] = ($allDay != "false") ? 1 : 0;
		
		// If all day delete the end dateTime
		// if not apply the delta to end dateTime
		if (isset($event['end'])) {
			$end_datetime = Carbon::parse($event['end']);
			$end_datetime = $end_datetime->add($delta);
			$data['end'] = $end_datetime->format("Y-m-d H:i");
		}
		
		// Log::Debug('Updating event: ' . var_export($event, true) . " with " . var_export($data, true));
		// update the event
		CalendarEvent::whereId ( $id )->update ( $data );
		
		$success = ['status' => 'OK'];
		$output = $success;
		return response()->json($output);
	}
	
	/**
	 * Called by fullcalendar when an event is dragged
	 *
	 * @param Request $request
	 * @return json status => 'OK' or ['error' => ['message' => 'error message', 'code' => 1234]];
	 * 
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function resized (Request $request) {
		$id = $request->get ('id');
		$title = $request->get ('title');
		$new_end = $request->get ('end');
		$allDay = $request->get ('allDay');
		
		Log::Debug("Event $id, title=$title, has been resized to end=$new_end, allDay=$allDay");
		
		if (! $id) {
			$output = ['error' => ['message' => 'Missing calendar event ID', 'code' => 1]];
			Log::Debug('Missing calendar event ID');
			return response()->json($output);
		}
		
		$end_datetime = null;
		if (! $new_end) {
			$output = ['error' => ['message' => 'Missing calendar event end', 'code' => 2]];
			Log::Debug('Missing calendar event end');
			
			return response()->json($output);
			
		} else {
			try {
				$exploded = explode(' ', $new_end);
				$end_datetime = Carbon::parse($exploded[0], Config::config('app.timezone'));
				
			} catch ( Exception $e ) {
				$output = ['error' => ['message' => 'Incorrect event end format', 'code' => 3]];
				Log::Debug('Incorrect event end format: ' . $new_end);
				return response()->json($output);
			}
		}
		
		// Fetch the event
		$event = CalendarEvent::find ($id);	

		if (! $event) {
			$output = ['error' => ['message' => 'Unknown calendar event ID', 'code' => 4]];
			Log::Debug('Unknown calendar event ID');
			return response()->json($output);
		}
		
		// Check that the start dateTime has not changed
				
		Log::debug("initial end = " . $event->end);
		Log::debug("new end     = " . $end_datetime->format('Y-m-d H:i e'));
		
		// update the event
		$end_datetime->setTimezone('UTC');
		
		$data = [];
		$data ['end'] = $end_datetime->format("Y-m-d H:i");
		
		CalendarEvent::whereId ( $id )->update ( $data );
		
		$success = ['status' => 'OK'];
		$output = $success;
		return response()->json($output);
	}
	
}
