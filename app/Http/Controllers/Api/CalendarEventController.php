<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenants\CalendarEvent;
use App\Http\Requests\Tenants\CalendarEventRequest;
use App\Helpers\DateFormat;
use Illuminate\Support\Str;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;


/**
 * REST API for Calendar Events
 *
 * @author frederic
 * @reviewed on 2021-08-01
 *        
 */
class CalendarEventController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * HTML parameters:
	 *
	 * @param
	 *        	int per_page number of item per page
	 * @param
	 *        	int page to return
	 * @param
	 *        	string sort comma separated column names, use minus sign for reverse OrderedTimeCodec
	 *          ex: sort=allDay,-start
	 * @param
	 *        	string filter ?filter=sex:female,color:brown
	 *        	
	 * function parameters
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 *
	 * @return an object with pagination information and the collection in the data field
	 *        
	 */
	public function index(Request $request) {
		// Laravel default is 15
		$per_page = ($request->has ( 'page' )) ?  $request->get ('per_page') : 1000000;
				
		$query = CalendarEvent::query();
		if ($request->has ('sort')) {
			
			$sorts = explode(',', $request->input ('sort'));
			
			foreach($sorts as $sortCol) {
				$sortDir = Str::startsWith( $sortCol, '-') ? 'desc' : 'asc';
				$sortCol = ltrim( $sortCol, '-');
				$query->orderBy($sortCol, $sortDir);
			}
		}
		
		if ($request->has ('filter')) {
			$filters = explode(',', $request->input ('filter'));
			
			foreach ($filters as $filter) {
				list($criteria, $value) = explode(':', $filter, 2);
				
				$operator_found = false;
				foreach (['<=', '>=', '<', '>'] as $op) {
					if (Str::startsWith($value, $op)) {
						$value = ltrim($value, $op);
						$query->where($criteria, $op, $value);
						$operator_found = true;
						break;
					}
				}
				if (!$operator_found) $query->where($criteria, $value);
			}
		}
		
		return $query->paginate ($per_page);
	}

	
	/**
	 * Special entry to for Ajax fullcalendar interface
	 *
	 */
	public function fullcalendar(Request $request) {
		
		Log::Debug("AJAX API fullcalendar");
		$start = $request->start;
		$end = $request->end;
		Log::Debug("start = " . $start);
		Log::Debug("end = " . $end);
		
		// [2021-12-19 15:22:44] local.DEBUG: start = 2021-11-29T00:00:00+01:00
		// [2021-12-19 15:23:53] local.DEBUG: end = 2022-01-10T00:00:00+01:00  
				
		$events = CalendarEvent::all()->where("start", ">=", $start)->where("start", "<=", $end);
		
		$json = [];
		/*
		 * When start or end contains a time the event is displayed
		 * with a colored dot followed by the starting time on a white background.
		 * 
		 * When there is no specified time, the event is displayed with a colored background.
		 * 
		 * https://fullcalendar.io/docs/event-source-object#options
		 * 
		 * http://abbeville.tenants.com/api/calendar/fullcalendar?start=2021-12-27T00:00:00+01:00&end=2022-02-07T00:00:00+01:00
		 * 
		 * Times must be returned as they are displayed according to timezone.local configuration value.

		Example:	
	
		title	"Docteur"
		start	"2022-01-05 08:00:00"
		end	"2022-01-05 00:00:00"
		id	12
		url	"http://abbeville.tenants.com/calendar/12/edit"
		backgroundColor	"#cc0000"
		color	"#ffff66"
		
		title	"dentist"
		start	"2022-01-03 09:15:00"
		end	"2022-01-03 14:00:00"
		id	18
		url	"http://abbeville.tenants.com/calendar/18/edit"
		backgroundColor	"#ffffff"
		color	"#000000"
		
		title	"RENDEZ VOUS"
		start	"2022-01-06"
		end	"2022-01-09"
		id	42
		url	"http://abbeville.tenants.com/calendar/42/edit"
		backgroundColor	"#ff0000"
		color	"#808080"
    	 */	
		
		foreach ($events as $event) {
			$evt = ["title" =>  $event->title,
					"start" => $event->getFullcalendarStart(),
					"end" => $event->getFullcalendarEnd(),
					"id" => $event->id,
					"url" => URL::to('/calendar_event') . "/" . $event->id . "/edit"
			];
			Log::debug(var_export($evt, true));
			
			if ($event->backgroundColor) {
				$evt['backgroundColor'] = $event->backgroundColor;
			}
			if ($event->textColor) {
				$evt['color'] = $event->textColor;
			}
			if ($event->borderColor) {
				$evt['borderColor'] = $event->borderColor;
			}
			$json[] = $evt;
		}
		
		return $json;		
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 * 
	 * 
	 */
	public function store(CalendarEventRequest $request) {
		$validatedData = $request->validated ();

		if (array_key_exists ( 'start_date', $validatedData ) && $validatedData ['start_date']) {
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start_date'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end_date', $validatedData ) && $validatedData ['end_date']) {
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end_date'], $validatedData ['end_time'] );
		}
		$validatedData ['allDay'] = $request->has ( 'allDay' );

		return CalendarEvent::create ( $validatedData );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function show($id) {
		return CalendarEvent::findOrFail ( $id );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function update(CalendarEventRequest $request, $id) {
		$validatedData = $request->validated ();

		if (array_key_exists ( 'start_date', $validatedData ) && $validatedData ['start_date']) {
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start_date'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end_date', $validatedData ) && $validatedData ['end_date']) {
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end_date'], $validatedData ['end_time'] );
		}
		$validatedData ['allDay'] = $request->has ( 'allDay' ) && $request->allDay;

		unset ( $validatedData ['start_date'] );
		unset ( $validatedData ['end_date'] );
		unset ( $validatedData ['start_time'] );
		unset ( $validatedData ['end_time'] );

		return CalendarEvent::whereId ( $id )->update ( $validatedData );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function destroy($id) {
		$calendarEvent = CalendarEvent::findOrFail ( $id );
		return $calendarEvent->delete ();
	}

}
