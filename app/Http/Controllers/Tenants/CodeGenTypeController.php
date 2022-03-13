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
    public function index() {
    	$code_gen_types = CodeGenType::all();
    	return view ( 'tenants/code_gen_type/index', compact ( 'code_gen_types' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() { 
    	return view ('tenants/code_gen_type/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\CodeGenTypeRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CodeGenTypeRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done
        
        $validatedData['takeoff'] = $validatedData['takeoff_date'] . ' ' . $validatedData['takeoff_time'] . ':00';
        
        if ($request->file('picture')) {
        	$name =  $request->file('picture')->getClientOriginalName();
        	$request->file('picture')->storeAs('uploads', $name);
        	$validatedData['picture'] = $name;
        }
        if (array_key_exists("picture", $validatedData) && !$validatedData['picture']) unset($validatedData['picture']);
        
        if ($request->file('attachment')) {
        	$name =  $request->file('attachment')->getClientOriginalName();
        	$request->file('attachment')->storeAs('uploads', $name);
        	$validatedData['attachment'] = $name;
        }
        if (array_key_exists("attachment", $validatedData) && !$validatedData['attachment']) unset($validatedData['attachment']);
        
        CodeGenType::create($validatedData);
        
        return redirect('/code_gen_type')->with('success', __('general.creation_success', [ 'elt' => __("code_gen_type.elt")]));
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\CodeGenType  $code_gen_type
     * @return \Illuminate\Http\Response
     */
    public function show(CodeGenType $code_gen_type) {
        // echo "CodeGenType.show\n";
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\CodeGenType  $code_gen_type
     * @return \Illuminate\Http\Response
     */
    public function edit(CodeGenType $code_gen_type) {
        return view('tenants/code_gen_type/edit')
            ->with(compact('code_gen_type'))
;
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\CodeGenTypeRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function update(CodeGenTypeRequest $request, $id) {
        $validatedData = $request->validated();
        
        $validatedData ['birthday'] = DateFormat::datetime_to_db ( $validatedData ['birthday']);
        $validatedData ['takeoff'] = DateFormat::datetime_to_db ( $validatedData ['takeoff_date'], $validatedData ['takeoff_time'] );
        unset($validatedData['takeoff_date']);
        unset($validatedData['takeoff_time']);
                
        if ($request->file('picture')) {
        	$name =  $request->file('picture')->getClientOriginalName();
        	$request->file('picture')->storeAs('uploads', $name);
        	$validatedData['picture'] = $name;
        }
        if (array_key_exists("picture", $validatedData) && !$validatedData['picture']) unset($validatedData['picture']);
        
        if ($request->file('attachment')) {
        	$name =  $request->file('attachment')->getClientOriginalName();
        	$request->file('attachment')->storeAs('uploads', $name);
        	$validatedData['attachment'] = $name;
        }
        if (array_key_exists("attachment", $validatedData) && !$validatedData['attachment']) unset($validatedData['attachment']);
        
        // var_dump($validatedData); exit;
        
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
    	$code_gen_type->delete();
    	return redirect ( 'code_gen_type' )->with ( 'success', __('general.deletion_success', ['elt' => $id]));
    }
}
