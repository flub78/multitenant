<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\CodeGenTypeRequest;
use App\Models\Tenants\CodeGenType;
use App\Helpers\DateFormat;
use Illuminate\Http\Request;
use Redirect;



/**
 * Controller for code_gen_type
 *
 * @author frederic
 *
 */
class CodeGenTypeController extends Controller {
    
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index(Request $request) {
	    
	    $query = CodeGenType::query();
	    
	    $filter_open = ($request->has ('filter_open')) ? "-show" : "";	    
	    if ($request->has ('filter')) {	        
	        $this->applyFilter($query, $request->input ('filter'));
	    }
	    $code_gen_types = $query->get ();   

		return view ( 'tenants/code_gen_type/index', compact ( 'code_gen_types' ))
		  ->with('filter_open', $filter_open);
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
        $color_name_list = ["blue" => __("code_gen_type.color_name.blue"),
        		"red" => __("code_gen_type.color_name.red"),
        		"green" => __("code_gen_type.color_name.green"),
        		"white" => __("code_gen_type.color_name.white"),
        		"black" => __("code_gen_type.color_name.black")];
    	return view ('tenants/code_gen_type/create')->with("color_name_list", $color_name_list);
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param App\Http\Requests\Tenants\CodeGenTypeRequest
	 * @return \Illuminate\Http\Response
	 */
	public function store(CodeGenTypeRequest $request) {
		$validatedData = $request->validated(); // Only retrieve the data, the validation is done
		
		$this->store_datetime($validatedData, 'takeoff');
		$this->store_bitfield($validatedData, "qualifications", $request, "code_gen_type");
        $this->store_checkbox($validatedData, "black_and_white", $request, "code_gen_type");
		$this->store_picture($validatedData, "picture", $request, "code_gen_type");
		$this->store_file($validatedData, "attachment", $request, "code_gen_type");
		
		CodeGenType::create($validatedData);
		
		return redirect('/code_gen_type')->with('success', __('general.creation_success', [ 'elt' => __("code_gen_type.elt")]));
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
	    $code_gen_type = CodeGenType::find($id);
	    
        $color_name_list = ["blue" => __("code_gen_type.color_name.blue"),
        		"red" => __("code_gen_type.color_name.red"),
        		"green" => __("code_gen_type.color_name.green"),
        		"white" => __("code_gen_type.color_name.white"),
        		"black" => __("code_gen_type.color_name.black")];
        return view('tenants/code_gen_type/show')
            ->with(compact('code_gen_type'))->with("color_name_list", $color_name_list);

	}
	
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Tenants\CodeGenType  $code_gen_type
	 * @return \Illuminate\Http\Response
	 */
	public function edit(CodeGenType $code_gen_type) {
	    $this->convert_datetime($code_gen_type, 'takeoff');   
        
        $color_name_list = ["blue" => __("code_gen_type.color_name.blue"),
        		"red" => __("code_gen_type.color_name.red"),
        		"green" => __("code_gen_type.color_name.green"),
        		"white" => __("code_gen_type.color_name.white"),        		
        		"black" => __("code_gen_type.color_name.black")];
        return view('tenants/code_gen_type/edit')
            ->with(compact('code_gen_type'))->with("color_name_list", $color_name_list);
	}
	
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param App\Http\Requests\Tenants\CodeGenTypeRequest;
	 * @param String $id
	 * @return \Illuminate\Http\Response
     * 
     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function update(CodeGenTypeRequest $request, $id) {
		$validatedData = $request->validated();
		$previous = CodeGenType::find($id);
		
		$this->store_datetime($validatedData, 'takeoff');		
		$this->update_bitfield($validatedData, "qualifications", $request, "code_gen_type");
        $this->store_checkbox($validatedData, "black_and_white", $request, "code_gen_type");
		$this->update_picture($validatedData, "picture", $request, "code_gen_type", $previous);
		$this->update_file($validatedData, "attachment", $request, "code_gen_type", $previous);
		
		CodeGenType::where([ 'id' => $id])->update($validatedData);
		
		return redirect('/code_gen_type')->with('success', __('general.modification_success', [ 'elt' => __("code_gen_type.elt") ]));
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Tenants\CodeGenType  $code_gen_type
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(CodeGenType $code_gen_type) {
		$id = $code_gen_type->id;
		
		if ($code_gen_type->picture) $this->destroy_file($code_gen_type->picture);
		if ($code_gen_type->attachment) $this->destroy_file($code_gen_type->attachment);
		
		$code_gen_type->delete();
		return redirect ( 'code_gen_type' )->with ( 'success', __('general.deletion_success', ['elt' => $id]));
	}
	
	/**
	 * Display a picture in the browser
	 * 
	 * @param unknown $id
	 * @param unknown $field
	 * @return \App\Http\Controllers\unknown
	 */
	public function picture($id, $field) {
        $elt = CodeGenType::find($id);
        if ($elt) {
        	$filename = $elt->$field;       
			return $this->displayImage($filename);
        }
	}
	
	/**
	 * Download a previously uploaded file
	 * 
	 * @param unknown $id
	 * @param unknown $field
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function download($id, $field) {
        $elt = CodeGenType::find($id);
        if ($elt) {
        	$filename = $elt->$field;
			return $this->download_file($filename);
        }
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
	        return redirect('/code_gen_type');
	    }
	    
	    /*
	     * Generate a filter string from the form inputs fields
	     * 
	     * Checkboxes and enumerates need an additonal checkbox to detemine if they must be taken into account
	     * by the filter
	     */
	    $filters_array = [];
	    $fields = ['name', 'description', 'year_of_birth', 'birthday', 'tea_time', 'takeoff', 'price'
	    ];
	    foreach ($fields as $field) {
	        if (array_key_exists($field, $inputs) && $inputs[$field]) {
	            $filters_array[] = $field . ':' . $inputs[$field];
	        }
	    }
	    $filter = implode(",", $filters_array);
	    
	    
        return redirect("/code_gen_type?filter=$filter&filter_open=1")->withInput();
	}
	
}
