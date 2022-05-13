<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\CodeGenTypesView1Request;
use App\Models\Tenants\CodeGenTypesView1;

/**
 * Controller for code_gen_types_view1
 * 
 * @author frederic
 *
 */
class CodeGenTypesView1Controller extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$code_gen_types_view1 = CodeGenTypesView1::all();
    	return view ( 'tenants/code_gen_types_view1/index', compact ( 'code_gen_types_view1' ) );
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\CodeGenTypesView1  $code_gen_types_view1
     * @return \Illuminate\Http\Response
     */
    public function show(CodeGenTypesView1 $code_gen_types_view1) {
    }

}
