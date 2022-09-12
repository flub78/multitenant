<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\RoleRequest;
use App\Models\Tenants\Role;
use App\Helpers\DateFormat;


/**
 * Controller for role
 * 
 * @author frederic
 *
 */
class RoleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$roles = Role::all();
    	return view ( 'tenants/role/index', compact ( 'roles' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    	return view ('tenants/role/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\RoleRequest
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done
        Role::create($validatedData);
       return redirect('/role')->with('success', __('general.creation_success', [ 'elt' => __("role.elt")]));
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
	    $role = Role::find($id);
        return view('tenants/role/show')
            ->with(compact('role'));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role) {
        return view('tenants/role/edit')
            ->with(compact('role'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\RoleRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(RoleRequest $request, $id) {
        $validatedData = $request->validated();
        $previous = Role::find($id);

        Role::where([ 'id' => $id])->update($validatedData);

        return redirect('/role')->with('success', __('general.modification_success', [ 'elt' => __("role.elt") ]));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
    	$id = $role->id;
    	$role->delete();
    	return redirect ( 'role' )->with ( 'success', __('general.deletion_success', ['elt' => $id]));
    }
}
