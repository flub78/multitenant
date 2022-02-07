<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\RoleRequest;
use App\Models\Tenants\Role;
use Illuminate\Database\QueryException;

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
     * @param  \Illuminate\Http\RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done
        Role::create($validatedData);

        return redirect('/role')->with('success', __('general.creation_success', [ 'elt' => $validatedData ['name']]));
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role) {
        //
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role) {
        return view('tenants/role/edit')->with(compact('role'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenants\Role  $role
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(RoleRequest $request, $id) {
        $validatedData = $request->validated();

        Role::where([ 'id' => $id])->update($validatedData);

        return redirect('/role')->with('success', __('general.modification_success', [ 'elt' => $validatedData ['name']
        ]));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
    	$full_name = $role->full_name;
    	$role->delete();
    	return redirect ( 'role' )->with ( 'success', __('general.deletion_success', ['elt' => $full_name]));
    }
}
