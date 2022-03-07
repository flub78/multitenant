<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\CodeGenTypeRequest;
use App\Models\Tenants\CodeGenType;

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
        //var_dump($validatedData); exit;
        CodeGenType::create($validatedData);
        
        return redirect('/code_gen_type')->with('success', __('general.creation_success', [ 'elt' => $validatedData ['id']]));
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

        CodeGenType::where([ 'id' => $id])->update($validatedData);

        return redirect('/code_gen_type')->with('success', __('general.modification_success', [ 'elt' => $validatedData ['id'] ]));
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
