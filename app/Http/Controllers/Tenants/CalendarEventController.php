<?php

namespace app\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\Tenants\CalendarEvent;
use App\Http\Requests\Tenants\CalendarEventRequest;
use App\Helpers\DateFormat;

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
		return view ( 'tenants.calendar.index', compact ( 'events' ) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function fullcalendar() {
		$events = CalendarEvent::all ();
		return view ( 'tenants.calendar.calendar', compact ( 'events' ) );
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
	public function create() {
		return view ( 'tenants.calendar.create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CalendarEventRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CalendarEventRequest $request) {
		$validatedData = $request->validated ();

		if (array_key_exists ( 'start', $validatedData ) && $validatedData ['start']) {
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end', $validatedData ) && $validatedData ['end']) {
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end'], $validatedData ['end_time'] );
		}
		$validatedData ['allDay'] = $request->has ( 'allDay' );

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
		return view ( 'tenants.calendar.edit' )->with ( 'calendarEvent', $calendarEvent );
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

}
