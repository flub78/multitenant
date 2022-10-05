<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\MotdTodayRequest;
use App\Models\Tenants\MotdToday;
use App\Helpers\DateFormat;
use Illuminate\Http\Request;
use Redirect;


/**
 * Controller for motd_today
 * 
 * @author frederic
 *
 */
class MotdTodayController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $query = MotdToday::query();

	    $filter_open = ($request->has ('filter_open')) ? "-show" : "";	    
	    if ($request->has ('filter')) {	        
	        $this->applyFilter($query, $request->input ('filter'));
	    }
	    $motd_todays = $query->get ();   

    	return view ( 'tenants/motd_today/index', compact ( 'motd_todays' ) )
            ->with('filter_open', $filter_open);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
	    $motd_today = MotdToday::find($id);
        return view('tenants/motd_today/show')
            ->with(compact('motd_today'));
    }

    

	/**
	 * This method is called by the filter form submit buttons
	 * Generate a filter parameter according to the filter form and call the index view
	 * 
	 * @param Request $request
	 */
	public function filter(Request $request) {
	    $inputs = $request->input();
	    
	    // When it is not the filter button redirect to index with no filtering
	    if ($request->input('button') != __('general.filter')) {
	        return redirect('/motd_today');
	    }
	    
	    /*
	     * Generate a filter string from the form inputs fields
	     * 
	     * Checkboxes and enumerates need an additonal checkbox to detemine if they must be taken into account
	     * by the filter
	     */
	    $filters_array = [];
	    $fields = [  ];
	    foreach ($fields as $field) {
	        if (array_key_exists($field, $inputs) && $inputs[$field]) {
	            $filters_array[] = $field . ':' . $inputs[$field];
	        }
	    }
	    $filter = implode(",", $filters_array);
	    
	    
        return redirect("/motd_today?filter=$filter&filter_open=1")->withInput();
	}
}
