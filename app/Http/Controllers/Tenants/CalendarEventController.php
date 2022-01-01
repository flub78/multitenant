<?php

namespace app\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\Tenants\CalendarEvent;
use App\Http\Requests\Tenants\CalendarEventRequest;
use Illuminate\Http\Request;
use App\Helpers\DateFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


/**
 * Calendar Events Controllers
 *
 * Calendar events are items contained in a Calendar. They have a date, start time, end time, etc.
 * To some extend this class is inspired form the Google and others calendar.
 *
 * Calendar is a tenant only feature. Central application is really limited to the tenant management.
 *
 * @author frederic
 *        
 */
class CalendarEventController extends Controller {

	// name of the table
	private $name = "calendar_events";

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$events = CalendarEvent::all ();
		// var_dump($events);exit;
		return view ( 'tenants.calendar_event.index', compact ( 'events' ) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function fullcalendar() {
		Log::Debug("fullcalendar display");
		
		$events = CalendarEvent::all ();
		return view ( 'tenants.calendar_event.calendar', compact ( 'events' ) );
	}

	/*
	 * TODO cleanup once there is an API controller for the same model
	 *
	 */
	public function json() {
		return '[
	 		{
	 			"title": "Event 1",
		 		"start": "2021-06-22T09:00:00",
				 "end": "2021-06-22T18:00:00",
	 "startEditable": true
	 },
	 {
	 "title": "Event 2",
	 "start": "2021-06-22",
	 "end": "2021-06-22",
	 "startEditable": true,
	 "durationEditable": true,
	 "backgroundColor": "lightBlue"
	 }
	 ]';
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {
		
		$data = ['action' => $request->get ('action')];
		if ($request->get ('start')) {
			$data['start'] = Carbon::createFromFormat('Y-m-d', $request->get ('start'))->format(__('general.date_format'));
		} else {
			$data['start'] = "";
		}
		return view ( 'tenants.calendar_event.create', $data );
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
		var_dump($validatedData); exit;
array (size=9)
  'title' => string 'Titre' (length=5)
  'description' => string 'Description' (length=11)
  'start' => string '14/12/2021' (length=10)
  'start_time' => string '10:20' (length=5)
  'end' => string '15/12/2021' (length=10)
  'end_time' => string '10:30' (length=5)
  'allDay' => string '1' (length=1)
  'backgroundColor' => string '#990000' (length=7)
  'textColor' => string '#38761d' (length=7)
*/

		if (array_key_exists ( 'start', $validatedData ) && $validatedData ['start']) {
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end', $validatedData ) && $validatedData ['end']) {
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end'], $validatedData ['end_time'] );
		}
		if (!array_key_exists ( 'backgroundColor', $validatedData )) {
			$validatedData ['backgroundColor'] = '#FFFFFF';
		}
		if (!array_key_exists ( 'textColor', $validatedData )) {
			$validatedData ['textColor'] = '#000000';
		}
		if ($validatedData ['backgroundColor'] == $validatedData ['textColor'] ) {
			$validatedData ['backgroundColor'] = '#FFFFFF';
			$validatedData ['textColor'] = '#000000';
		}
		CalendarEvent::create ( $validatedData );

		return redirect ( 'calendar' )->with ( 'success', __ ( 'general.creation_success', [ 
				'elt' => $validatedData ['title']
		] ) );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Tenants\CalendarEvent $calendarEvent
	 * @return \Illuminate\Http\Response
	 */
	public function show(CalendarEvent $calendarEvent) {
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $id of
	 *        	the calendar event to edit
	 *        	the event
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$calendarEvent = CalendarEvent::findOrFail ( $id );
		// var_dump($calendarEvent);
		// exit;
		return view ( 'tenants.calendar_event.edit' )->with ( 'calendarEvent', $calendarEvent );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param CalendarEventRequest $request
	 * @param
	 *        	id of the event
	 * @return \Illuminate\Http\Response
	 */
	public function update(CalendarEventRequest $request, $id) {
		$validatedData = $request->validated ();

		if (array_key_exists ( 'start', $validatedData ) && $validatedData ['start']) {
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end', $validatedData ) && $validatedData ['end']) {
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end'], $validatedData ['end_time'] );
		}
		$validatedData ['allDay'] = $request->has ( 'allDay' ) && $request->allDay;

		unset ( $validatedData ['start_time'] );
		unset ( $validatedData ['end_time'] );

		// var_dump($validatedData); exit;

		CalendarEvent::whereId ( $id )->update ( $validatedData );

		return redirect ( 'calendar' )->with ( 'success', __ ( 'general.modification_success', [ 
				'elt' => $validatedData ['title']
		] ) );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $id of
	 *        	the event
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(string $id) {
		// CalendarEvent $calendarEvent
		$calendarEvent = CalendarEvent::findOrFail ( $id );
		$title = $calendarEvent->title;
		$calendarEvent->delete ();

		return redirect ( 'calendar' )->with ( 'success', __('general.deletion_success', ['elt' => $title]));		
	}

	/**
	 * Called by fullcalendar when an event is dragged
	 * 
	 * @param Request $request
	 * @return json status => 'OK' or ['error' => ['message' => 'error message', 'code' => 1234]];
	 */
	public function dragged (Request $request) {
		
		$id = $request->get ('id');
		$title = $request->get ('title');
		$start = $request->get ('start');
		$end = $request->get ('end');
		$allDay = $request->get ('allDay');
		
		Log::Debug("Event $id, title=$title, has been draggged to $start end=$end, allDay=$allDay");
		
		if (! $id) {
			$output = ['error' => ['message' => 'Missing calendar event ID', 'code' => 1]];
			return response()->json($output);
		}
		
		if (! $id) {
			$output = ['error' => ['message' => 'Missing calendar event start', 'code' => 2]];
			return response()->json($output);
		}
		
		// Fetch the event
		$event = CalendarEvent::find ($id);	
		
		if (! $event) {
			$output = ['error' => ['message' => 'Unknown calendar event ID', 'code' => 3]];
			return response()->json($output);
		}
		
		// compute the difference between initial and last position (in seconds)
		// apply the delta to start dateTime
		
		// If all day delete the end dateTime
		// if not apply the delta to end dateTime
		
		// update the event
		
		$success = ['status' => 'OK'];
		$output = $success;
		return response()->json($output);
	}
	
	/**
	 * Called by fullcalendar when an event is dragged
	 *
	 * @param Request $request
	 * @return json status => 'OK' or ['error' => ['message' => 'error message', 'code' => 1234]];
	 */
	public function resized (Request $request) {
		$id = $request->get ('id');
		$title = $request->get ('title');
		$start = $request->get ('start');
		$end = $request->get ('end');
		$allDay = $request->get ('allDay');
		
		Log::Debug("Event $id, title=$title, has been resized to $start end=$end, allDay=$allDay");
		
		// Fetch the event
		// Check that the start dateTime has not changed
		
		// compute the difference between initial and last end position (in seconds)
		// apply the delta to end dateTime
		
		// update the event
	}
	
}
