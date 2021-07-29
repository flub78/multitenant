<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenants\CalendarEvent;
use App\Http\Requests\Tenants\CalendarEventRequest;
use App\Helpers\DateFormat;


/**
 * REST API for Calendar Events
 * 
 * @author frederic
 *
 */
class CalendarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
    {
    	$per_page = $request->get('per_page');
    	// $page = $request->get('page');
    	return CalendarEvent::paginate($per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarEventRequest $request)
    {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	return CalendarEvent:: findOrFail( $id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CalendarEventRequest $request, $id)
    {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$calendarEvent = CalendarEvent::findOrFail ( $id );
    	return $calendarEvent->delete ();
    }
}
