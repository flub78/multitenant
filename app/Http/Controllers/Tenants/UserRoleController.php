<?php

namespace App\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenants\Role;
use App\Models\Tenants\UserRole;
use Illuminate\Http\Request;
use App\Helpers\HtmlHelper;


/**
 * Controller to assign a role to a user
 * 
 * TODO: manage unicity
 * TODO: complete destroy and edit
 * TODO: controller phpunit
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
    public function index()
    {
    	$user_roles = UserRole::all();
    	return view ( 'tenants/user_role/index', compact ( 'user_roles' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$user_list = User::selector();
    	$role_list = Role::selector();
    	return view ('tenants/user_role/create')
    		->with('role_list', $role_list)
    		->with('user_list', $user_list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $role_id = $request->role_id;
        UserRole::create(['user_id' => $user_id, 'role_id' => $role_id]);
        return redirect ( '/user_role' )->with ( 'success',  __('general.creation_success', ['elt' => "user=$user_id role=$role_id"]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $userRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
    	echo "destroying";
    }
}
