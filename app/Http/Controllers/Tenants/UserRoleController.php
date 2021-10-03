<?php

namespace App\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Http\Requests\Tenants\UserRoleRequest;
use App\Models\User;
use App\Models\Tenants\Role;
use App\Models\Tenants\UserRole;
use Illuminate\Database\QueryException;


/**
 * Controller to assign a role to a user
 * 
 * 
 * @author frederic
 *
 */
class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$user_roles = UserRole::all();
    	return view ( 'tenants/user_role/index', compact ( 'user_roles' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    	$user_list = User::selector();
    	$role_list = Role::selector();
    	return view ('tenants/user_role/create')
    		->with('role_list', $role_list)
    		->with('user_list', $user_list);   		;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRoleRequest $request) {
        $user_id = $request->user_id;
        $role_id = $request->role_id;
        try {
        	$id = UserRole::create(['user_id' => $user_id, 'role_id' => $role_id]);
        	return redirect ( '/user_role' )->with ( 'success',  __('user_roles.created', ['user' => $id->user_name, 'role'=> $id->role_name]));
        } catch (QueryException $e) {
        	// very likely a duplicate
        	return redirect ( '/user_role' );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response

    public function show(UserRole $userRole) {
        //
    }
     */
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $user_role) {
    	$user_list = User::selector();
    	$role_list = Role::selector();
    	return view('tenants/user_role/edit')
    	->with('role_list', $role_list)
    	->with('user_list', $user_list)
    	->with(compact('user_role'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(UserRoleRequest $request, $id) {
    	$validatedData = $request->validated();
    	
    	$user_role = UserRole::where([ 'id' => $id])->first();
    	$user_role->update($validatedData);
    	
    	return redirect('/user_role')->with('success', __('general.modification_success', [ 'elt' => $user_role->full_name
    	]));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole) {
    	$full_name = $userRole->full_name;
    	$userRole->delete();
    	return redirect ( 'user_role' )->with ( 'success', __('general.deletion_success', ['elt' => $full_name]));
    }
}
