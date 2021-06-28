<?php

namespace app\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\Tenants\CalendarEvent;
use Illuminate\Http\Request;
use App\Http\Requests\Tenants\CalendarEventRequest;
use App\Helpers\DateFormat;

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
		foreach ($events as $event) {
			// var_dump($event);exit;
		}
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

	public function json2() {
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
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CalendarEventRequest $request) {
		$validatedData = $request->validated ();

		var_dump($validatedData);
		//CalendarEvent::create();
		echo "store\n";
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Tenants\CalendarEvent $calendarEvent
	 * @return \Illuminate\Http\Response
	 */
	public function show(CalendarEvent $calendarEvent) {
		echo "show\n";
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Tenants\CalendarEvent $calendarEvent
	 * @return \Illuminate\Http\Response
	 */
	public function edit(CalendarEvent $calendarEvent) {
		echo "edit\n";
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Tenants\CalendarEvent $calendarEvent
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, CalendarEvent $calendarEvent) {
		echo "update\n";
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\CalendarEvent  $calendarEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
    	echo "destroy\n";
    }
}
