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
use Carbon\Carbon;


/**
 * REST API for Calendar Events
 *
 * @author frederic
 *         reviewed on 2021/08/01
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

	/*
	 * Returns the date part of a date time
	 * dateOf("2021-11-29T00:00:00+01:00") => "2021-11-29"
	 * dateOf("2022-01-10") => "2022-01-10"
	 */
	function dateOf(String $dateTime) {
		$carbon  = new Carbon($dateTime);
		return $carbon->toDateString();
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
    	 */
		
		$json =  [
				[
						"title" => "Event 1",
						"start" => "2021-12-05T09:00:00",
						"end" => "2021-12-05T18:00:00",
						"color" => "green",
						"textColor" => "yellow"				
				],
				[
						"title" => "Event 2",
						"start" => "2021-12-08",
						"end" => "2021-12-08",
						"color" => "pink",
						"textColor" => "orange"				
				]
		];
	
		
		foreach ($events as $event) {
			$evt = ["title" =>  $event->title,
					"start" => $event->start,
					"end" => $event->end
			];
			if ($event->backgroundColor) {
				$evt['backgroundColor'] = $event->backgroundColor;
			}
			if ($event->textColor) {
				$evt['color'] = $event->textColor;
			}
			if ($event->borderColor) {
				$evt['borderColor'] = $event->borderColor;
			}
			if ($event->allDay) {
				$evt['start'] = $this->dateOf($event->start);
				$evt['end'] = $this->dateOf($event->end);
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
	 */
	public function store(CalendarEventRequest $request) {
		$validatedData = $request->validated ();

		// CalendarEvent::create( $request-> all());
		if (array_key_exists ( 'start', $validatedData ) && $validatedData ['start']) {
			$validatedData ['start'] = DateFormat::datetime_to_db ( $validatedData ['start'], $validatedData ['start_time'] );
		}
		if (array_key_exists ( 'end', $validatedData ) && $validatedData ['end']) {
			$validatedData ['end'] = DateFormat::datetime_to_db ( $validatedData ['end'], $validatedData ['end_time'] );
		}
		$validatedData ['allDay'] = $request->has ( 'allDay' );

		return CalendarEvent::create ( $validatedData );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
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
	 */
	public function update(CalendarEventRequest $request, $id) {
		// $event = CalendarEvent::findOrFail($id);
		// return $event->update($request);
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

		return CalendarEvent::whereId ( $id )->update ( $validatedData );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$calendarEvent = CalendarEvent::findOrFail ( $id );
		return $calendarEvent->delete ();
	}

}
