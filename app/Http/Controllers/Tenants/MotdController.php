<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\MotdRequest;
use App\Models\Tenants\Motd;
use App\Helpers\DateFormat;
use Illuminate\Http\Request;
use Redirect;


/**
 * Controller for motd
 * 
 * @author frederic
 *
 */
class MotdController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $query = Motd::query();

	    $filter_open = ($request->has ('filter_open')) ? "-show" : "";	    
	    if ($request->has ('filter')) {	        
	        $this->applyFilter($query, $request->input ('filter'));
	    }
	    $motds = $query->get ();   

    	return view ( 'tenants/motd/index', compact ( 'motds' ) )
            ->with('filter_open', $filter_open);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() { 
    	return view ('tenants/motd/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\MotdRequest
     * @return \Illuminate\Http\Response
     */
    public function store(MotdRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done

        Motd::create($validatedData);
       return redirect('/motd')->with('success', __('general.creation_success', [ 'elt' => __("motd.elt")]));
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
	    $motd = Motd::find($id);
        return view('tenants/motd/show')
            ->with(compact('motd'));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Motd  $motd
     * @return \Illuminate\Http\Response
     */
    public function edit(Motd $motd) {
        return view('tenants/motd/edit')
            ->with(compact('motd'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\MotdRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     * 
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(MotdRequest $request, $id) {
        $validatedData = $request->validated();
        $previous = Motd::find($id);

        Motd::where([ 'id' => $id])->update($validatedData);

        return redirect('/motd')->with('success', __('general.modification_success', [ 'elt' => __("motd.elt") ]));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Motd  $motd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Motd $motd) {
    	$id = $motd->id;
    	$motd->delete();
    	return redirect ( 'motd' )->with ( 'success', __('general.deletion_success', ['elt' => $id]));
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
	        return redirect('/motd');
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
	    
	    
        return redirect("/motd?filter=$filter&filter_open=1")->withInput();
	}
}
